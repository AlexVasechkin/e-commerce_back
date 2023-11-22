<?php

namespace App\Controller\Pages;

use App\Application\Services\Elasticsearch\Elasticsearch;
use App\Application\Services\YandexMarket\YandexMarketXmlGenerator;
use App\Entity\ProductCategory;
use App\Repository\ProductCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class YandexMarketController extends AbstractController
{
    /**
     * @Route("/exports/yandex-market/full", methods={"GET"})
     */
    public function exportAll(
        YandexMarketXmlGenerator $generator,
        Elasticsearch $es,
        ProductCategoryRepository $productCategoryRepository,
        ParameterBagInterface $parameterBag
    ) {
        $response = $es->client->search(['index' => ['products'], 'body' => [
            'query' => ['range' => ['id' => ['gt' => 0]]]
        ]]);

        $data = [];
        $data['name'] = $parameterBag->get('app.project.name');
        $data['company'] = $parameterBag->get('app.project.legal_name');
        $data['url'] = sprintf('http%s://%s', $_SERVER['SERVER_PORT'] == '443' ? 's' : '', $_SERVER['HTTP_HOST']);
        $data['platform'] = 'Symfony';

        $data['offers'] = array_map(function ($item) {
            return $item['_source'];
        }, $response->asArray()['hits']['hits']);

        $data['categories'] = $productCategoryRepository->fetchAll();

        $document = $generator->generate($data);
        return new Response($document->asXML(), 200, ['Content-Type' => 'application/xml']);
    }
}
