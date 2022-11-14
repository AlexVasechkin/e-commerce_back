<?php

namespace App\Command;

use App\Entity\ProductCategoryItem;
use App\Repository\ProductCategoryItemRepository;
use App\Repository\ProductCategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateProductCategoryItemCommand extends Command
{
    protected static $defaultName = 'app:product-category-item:create';
    protected static $defaultDescription = 'Add a short description for your command';

    private ProductCategoryItemRepository $productCategoryItemRepository;

    private ProductRepository $productRepository;

    private ProductCategoryRepository $productCategoryRepository;

    public function __construct(
        string $name = null,
        ProductCategoryItemRepository $productCategoryItemRepository,
        ProductRepository $productRepository,
        ProductCategoryRepository $productCategoryRepository
    ) {
        parent::__construct($name);
        $this->productCategoryItemRepository = $productCategoryItemRepository;
        $this->productRepository = $productRepository;
        $this->productCategoryRepository = $productCategoryRepository;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('productId', InputArgument::REQUIRED, '')
            ->addArgument('categoryId', InputArgument::REQUIRED, '')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $productId = $input->getArgument('productId');
        $categoryId = $input->getArgument('categoryId');

        $this->productCategoryItemRepository->save(
            (new ProductCategoryItem())
                ->setProduct($this->productRepository->findOneBy(['id' => $productId]))
                ->setCategory($this->productCategoryRepository->findOneBy(['id' => $categoryId]))
        , true);

        $io->success('Ok.');

        return Command::SUCCESS;
    }
}
