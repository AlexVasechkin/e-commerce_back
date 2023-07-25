<?php

namespace App\Command;

use App\Application\Actions\Product\FilterByFullNameAction;
use App\Message\ParseProductPriceMessage;
use App\Repository\ProductGroupCategoryItemRepository;
use App\Repository\ProductGroupItemRepository;
use App\Repository\ProductGroupRepository;
use App\Repository\ProductRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

class TestCommand extends Command
{
    protected static $defaultName = 'app:test';
    protected static $defaultDescription = 'Test command';

    private ProductRepository $productRepository;

    public function __construct(
        string $name = null,
        ProductRepository $productRepository
    ) {
        parent::__construct($name);
        $this->productRepository = $productRepository;
    }

    protected function configure(): void
    {}

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
//            $htmlContent = file_get_contents('https://worldguns.store/optika/voennaya-optika/kollimatornye-pricely/kollimatornyy-pricel-aimpoint-9000sc-nv/');
//            $this->messageBus->dispatch(new ParseProductPriceMessage(265));
            echo count($this->productRepository->getFullIdList());
            dd($this->productRepository->getFullIdList());
        } catch (\Throwable $e) {
            $io->error(implode(PHP_EOL, [$e->getMessage(), $e->getTraceAsString()]));
            return Command::FAILURE;
        }

        $io->success('Ok.');
        return Command::SUCCESS;
    }
}
