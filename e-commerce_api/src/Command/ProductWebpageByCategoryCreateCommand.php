<?php

namespace App\Command;

use App\Application\Actions\ProductWebpage\CreateProductWebpageFromProductAction;
use App\Repository\ProductCategoryItemRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductWebpageRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ProductWebpageByCategoryCreateCommand extends Command
{
    protected static $defaultName = 'app:product:webpage-by-category:create';
    protected static $defaultDescription = 'Command to create product pages in category';

    private ProductRepository $productRepository;

    private ProductCategoryItemRepository $productCategoryItemRepository;

    private ProductWebpageRepository $productWebpageRepository;

    private CreateProductWebpageFromProductAction $createProductWebpageFromProductAction;

    public function __construct(
        string $name = null,
        ProductCategoryItemRepository $productCategoryItemRepository,
        ProductRepository $productRepository,
        ProductWebpageRepository $productWebpageRepository,
        CreateProductWebpageFromProductAction $createProductWebpageFromProductAction
    ) {
        parent::__construct($name);
        $this->productCategoryItemRepository = $productCategoryItemRepository;
        $this->productRepository = $productRepository;
        $this->productWebpageRepository = $productWebpageRepository;
        $this->createProductWebpageFromProductAction = $createProductWebpageFromProductAction;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('productCategoryId', InputArgument::REQUIRED, 'Product category id')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $productCategoryId = intval($input->getArgument('productCategoryId'));

            $toCreateWebpageIdList = array_diff(
                $this->productCategoryItemRepository->filterByCategories([$productCategoryId]),
                $this->productWebpageRepository->filterProductsHasPage()
            );

            foreach ($toCreateWebpageIdList as $productId) {
                try {
                    $this->createProductWebpageFromProductAction->execute(
                        $this->productRepository->findOneByIdOrFail($productId)
                    );
                    $io->success(sprintf('%s ok.', $productId));
                } catch (\Throwable $e) {
                    $io->error($e->getMessage());
                }
            }

            $io->success('Ok.');

            return Command::SUCCESS;

        } catch (\Throwable $e) {
            $io->error(implode(PHP_EOL, [
                $e->getMessage(),
                $e->getTraceAsString()
            ]));
            return Command::FAILURE;
        }
    }
}
