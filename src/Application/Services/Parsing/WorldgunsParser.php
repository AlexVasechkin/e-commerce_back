<?php

namespace App\Application\Services\Parsing;

use App\Application\Services\Parsing\Contracts\GetWebpageContentInterface;
use App\Application\Services\Parsing\Contracts\ProductParserInterface;

class WorldgunsParser
    implements ProductParserInterface
{
    public function parsePrice(GetWebpageContentInterface $webpageContentDto): ?string
    {
        $document = \phpQuery::newDocument($webpageContentDto->getWebpageContent());

        foreach ($document->find('[id^="sec_discounted_price_"]') as $key => $value) {
            $element = pq($value);
            return is_string($element->text()) ? $element->text() : null;
        }

        return null;
    }
}
