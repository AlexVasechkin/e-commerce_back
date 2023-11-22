<?php

namespace App\Command;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\VendorRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateProductCommand extends Command
{
    protected static $defaultName = 'app:product:create';
    protected static $defaultDescription = 'Creates product';

    private ProductRepository $productRepository;

    private VendorRepository $vendorRepository;

    public function __construct(
        string $name = null,
        ProductRepository $productRepository,
        VendorRepository $vendorRepository
    ) {
        parent::__construct($name);
        $this->productRepository = $productRepository;
        $this->vendorRepository = $vendorRepository;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('code', InputArgument::REQUIRED, '')
            ->addArgument('vendor', InputArgument::REQUIRED, '')
            ->addArgument('name', InputArgument::REQUIRED, '')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->productRepository->create(
            (new Product())
                ->setCode($input->getArgument('code'))
                ->setVendor($this->vendorRepository->findOneBy(['name' => $input->getArgument('vendor')]))
                ->setName($input->getArgument('name'))
                ->setLength(0)
                ->setWidth(0)
                ->setHeight(0)
                ->setMass(0)
        );

        $io->success('ok');

        return Command::SUCCESS;
    }
}
