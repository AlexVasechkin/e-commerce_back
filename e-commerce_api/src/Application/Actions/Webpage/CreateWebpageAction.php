<?php

namespace App\Application\Actions\Webpage;

use App\Entity\Contracts\PutWebpageInterface;
use App\Entity\Webpage;
use App\Repository\WebpageRepository;

class CreateWebpageAction
{
    private WebpageRepository $webpageRepository;

    public function __construct(WebpageRepository $webpageRepository)
    {
        $this->webpageRepository = $webpageRepository;
    }

    public function execute(PutWebpageInterface $request): Webpage
    {
        return $this->webpageRepository->save((new Webpage())->put($request), true);
    }
}
