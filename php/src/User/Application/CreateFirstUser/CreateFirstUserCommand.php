<?php

declare(strict_types=1);

namespace App\User\Application\CreateFirstUser;

use App\User\Domain\CreateFirstUserService;
use App\User\Domain\FirstUserAlreadyExistException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    'app:user:create_first',
    'Command creates first user if he does not exist'
)]
class CreateFirstUserCommand extends Command
{
    public function __construct(
        private readonly CreateFirstUserService $service,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $user = $this->service->execute();
            $this->entityManager->flush();

            $io->success("First user {$user->id} created successfully");
        } catch (FirstUserAlreadyExistException $e) {
            $io->warning($e->getMessage());
        }

        return Command::SUCCESS;
    }
}
