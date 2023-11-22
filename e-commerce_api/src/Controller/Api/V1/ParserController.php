<?php

namespace App\Controller\Api\V1;

use App\Application\Services\Parsing\ParserFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ParserController extends AbstractController
{
    /**
     * @Route("/api/v1/private/parser/dict", methods={"GET"})
     */
    public function getDict()
    {
        return $this->json([
            'payload' => array_map(function (array $row) {
                return [
                    'code' => $row['code'],
                    'name' => $row['name']
                ];
            }, ParserFactory::getParsersDataSet())
        ]);
    }
}
