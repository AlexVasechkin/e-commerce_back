<?php

namespace App\Command;

use App\Application\Actions\Product\Elasticsearch\FetchElasticsearchProductsAction;
use App\Application\Actions\Product\FilterProduct\DTO\FilterProductRequest;
use App\Application\Actions\Product\FilterProduct\FilterProductAction;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TestCommand extends Command
{
    protected static $defaultName = 'app:test';
    protected static $defaultDescription = 'Test command';

    private FilterProductAction $filterProductAction;

    private FetchElasticsearchProductsAction $fetchElasticsearchProductsAction;

    public function __construct(
        string $name = null,
        FilterProductAction $filterProductAction,
        FetchElasticsearchProductsAction $fetchElasticsearchProductsAction
    ) {
        parent::__construct($name);
        $this->filterProductAction = $filterProductAction;
        $this->fetchElasticsearchProductsAction = $fetchElasticsearchProductsAction;
    }

    protected function configure(): void
    {}

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $idList = $this->filterProductAction->execute(
                (new FilterProductRequest())
//                    ->addProperty('1', ['15.6'])
            );

            dd($this->fetchElasticsearchProductsAction->execute($idList));

        } catch (\Throwable $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }

        $io->success('Ok.');
        return Command::SUCCESS;
    }
}
