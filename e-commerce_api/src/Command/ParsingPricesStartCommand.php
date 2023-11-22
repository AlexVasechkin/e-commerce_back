<?php

namespace App\Command;

use App\Message\ParseProductPriceMessage;
use App\Repository\ProductRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

class ParsingPricesStartCommand extends Command
{
    protected static $defaultName = 'app:parsing:prices:start';
    protected static $defaultDescription = 'Command to start price parsing';

    private LoggerInterface $logger;

    private ProductRepository $productRepository;

    private MessageBusInterface $messageBus;

    public function __construct(
        string $name = null,
        LoggerInterface $logger,
        ProductRepository $productRepository,
        MessageBusInterface $messageBus
    ) {
        parent::__construct($name);
        $this->logger = $logger;
        $this->productRepository = $productRepository;
        $this->messageBus = $messageBus;
    }

    protected function configure(): void
    {}

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {

            foreach ($this->productRepository->filterToParsePrice() as $productId) {
                $this->messageBus->dispatch(new ParseProductPriceMessage($productId));
            }

            $io->success('Ok.');

            return Command::SUCCESS;

        } catch (\Throwable $e) {

            $error = implode(PHP_EOL, [
                $e->getMessage(),
                $e->getTraceAsString()
            ]);

            $this->logger->error($error);

            $io->error($error);

            return Command::FAILURE;
        }
    }
}
