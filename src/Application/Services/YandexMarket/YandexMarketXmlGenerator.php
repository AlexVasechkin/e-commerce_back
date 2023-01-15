<?php

namespace App\Application\Services\YandexMarket;

use SimpleXMLElement;
use DateTime;

class YandexMarketXmlGenerator
{
    public function addOffers(SimpleXMLElement $xmlOffers, array &$data): void
    {
        foreach ($data['offers'] as $offer) {
            $xmlOffer = $xmlOffers->addChild('offer');
            $xmlOffer->addAttribute('id', $offer['id'] ?? '');
            $xmlOffer->addChild('name', $offer['name'] ?? '');
            $xmlOffer->addChild('price', strval(round($offer['price'] ?? 0.00)));

        }
    }

    public function addCategories(SimpleXMLElement $xmlCategories, array &$data): void
    {
        foreach ($data['categories'] as $category) {
            $xmlCategory = $xmlCategories->addChild('category', $category['name']);
            $xmlCategory->addAttribute('id', strval($category['id']));

            if ($category['parent_id']) {
                $xmlCategory->addAttribute('parentId', strval($category['parent_id']));
            }
        }
    }

    public function generate(array &$data): SimpleXMLElement
    {
        $document = new SimpleXMLElement(implode(PHP_EOL, [
            '<?xml version="1.0" encoding="UTF-8"?>',
            sprintf('<yml_catalog date="%s">', (new DateTime())->format('Y-m-d\TH:i:s')),
            '</yml_catalog>'
        ]));

        $shop = $document->addChild('shop');

        $shop->addChild('name', $data['name']);
        $shop->addChild('company', $data['company']);
        $shop->addChild('url', $data['url']);
        $shop->addChild('platform', $data['platform']);

        $this->addCategories($shop->addChild('categories'), $data);

        $this->addOffers($shop->addChild('offers'), $data);

        return $document;
    }
}
