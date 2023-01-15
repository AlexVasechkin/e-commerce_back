<?php

namespace App\Command;

use App\Application\Actions\User\CreateUserAction;
use App\Application\Actions\User\DTO\CreateUserRequest;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:user:create';
    protected static $defaultDescription = 'Creates user';

    private CreateUserAction $createUserAction;

    private LoggerInterface $logger;

    public function __construct(
        string $name = null,
        CreateUserAction $createUserAction,
        LoggerInterface $logger
    ) {
        parent::__construct($name);
        $this->createUserAction = $createUserAction;
        $this->logger = $logger;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'email')
            ->addArgument('username', InputArgument::REQUIRED, 'username')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $user = $this->createUserAction->execute(new CreateUserRequest(
                $input->getArgument('username'),
                $input->getArgument('email'),
                ['ROLE_USER']
            ));
            $io->success(sprintf('User[id: %s] created successfully.', $user->getUserIdentifier()));
            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->logger->error($e->getTraceAsString());
            $io->error($e->getMessage());
            return Command::FAILURE;
        }
    }
}
