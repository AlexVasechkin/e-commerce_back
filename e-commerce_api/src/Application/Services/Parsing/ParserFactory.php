<?php

namespace App\Application\Services\Parsing;

use App\Application\Services\Parsing\Contracts\ProductParserInterface;

class ParserFactory
{
    public static function createParser(string $parserCode): ProductParserInterface
    {
        $class = self::getParsersDict()[$parserCode] ?? null;

        if (is_null($class)) {
            throw new \Exception('Parser implementation not found!');
        }

        return new $class();
    }

    public static function getParsersDataSet(): array
    {
        return [
            [
                'code' => 'worldguns',
                'name' => 'Worldguns',
                'service' => WorldgunsParser::class
            ]
        ];
    }

    public static function getParsersDict(): array
    {
        $r = [];

        foreach (self::getParsersDataSet() as $row) {
            $r[$row['code']] = $row['service'];
        }

        return $r;
    }
}
