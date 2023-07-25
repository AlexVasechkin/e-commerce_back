<?php

namespace App\Command;

use App\Application\Actions\Product\IndexProductAction;
use App\Repository\ProductRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class IndexAllProductsCommand extends Command
{
    protected static $defaultName = 'app:product:index-all';

    protected static $defaultDescription = 'Indexes all products to elasticsearch';

    private ProductRepository $productRepository;

    private IndexProductAction $indexProductAction;

    public function __construct(
        string $name = null,
        ProductRepository $productRepository,
        IndexProductAction $indexProductAction
    ) {
        parent::__construct($name);
        $this->productRepository = $productRepository;
        $this->indexProductAction = $indexProductAction;
    }

    protected function configure(): void
    {}

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        try {
            foreach ($this->productRepository->findAll() as $product) {
                $this->indexProductAction->execute($product->getId());
            }
        } catch (\Throwable $e) {
            $io->error($e->getMessage());
        }

        $io->success('Ok.');
        return Command::SUCCESS;
    }
}
