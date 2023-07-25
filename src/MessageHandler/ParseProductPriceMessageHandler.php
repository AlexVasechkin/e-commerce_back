<?php

namespace App\MessageHandler;

use App\Application\Services\Parsing\DTO\WebpageContent;
use App\Application\Services\Parsing\ParserFactory;
use App\Message\ParseProductPriceMessage;
use App\Repository\ProductRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ParseProductPriceMessageHandler
    implements MessageHandlerInterface
{
    private LoggerInterface $logger;

    private ProductRepository $productRepository;

    private HttpClientInterface $httpClient;

    public function __construct(
        LoggerInterface $logger,
        ProductRepository $productRepository,
        HttpClientInterface $httpClient
    ) {
        $this->logger = $logger;
        $this->productRepository = $productRepository;
        $this->httpClient = $httpClient;
    }

    public function __invoke(ParseProductPriceMessage $message)
    {
        try {
            $product = $this->productRepository->findOneByIdOrFail($message->getProductId());

            if (!is_string($product->getParserCode())) {
                throw new \Exception(sprintf('Parser code %s for product %s is null!', $product->getParserCode(), $product->getId()));
            }

            if (!is_string($product->getDonorUrl())) {
                throw new \Exception(sprintf('Donor url expected string'));
            }

            if (!in_array($product->getParserCode(), array_keys(ParserFactory::getParsersDict()))) {
                throw new \Exception(sprintf('Parser code %s not in allowed list', $product->getParserCode()));
            }

            $parser = ParserFactory::createParser($product->getParserCode());

            $httpResponse = $this->httpClient->request(
                'GET',
                $product->getDonorUrl()
            );

            if (200 !== $httpResponse->getStatusCode()) {
                throw new \Exception('Error get page content');
            }

            $priceText = $parser->parsePrice(new WebpageContent($httpResponse->getContent()));

            if (is_null($priceText)) {
                throw new \Exception(sprintf('Price element not detected for product %s', $product->getId()));
            }

            if (!is_numeric($priceText)) {
                throw new \Exception(sprintf('Error convert %s to int value for product %s', $priceText, $product->getId()));
            }

            $this->productRepository->save($product->setPrice(intval(round(floatval($priceText)))));

        } catch (\Throwable $e) {
            $this->logger->error(implode(PHP_EOL, [
                $e->getMessage(),
                $e->getTraceAsString()
            ]));
        }
    }
}
