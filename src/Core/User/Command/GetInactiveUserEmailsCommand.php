<?php

namespace App\Core\User\Command;

use App\Core\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:user:get-inactive-emails',
    description: 'Get emails of inactive users.',
)]
class GetInactiveUserEmailsCommand extends Command
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $emails = $this->userRepository->getInactiveUserEmails();
        if (empty($emails)) {
            $output->writeln('<comment>Brak nieaktywnych użytkowników.</comment>');
        } else {
            $output->writeln('<info>Nieaktywni użytkownicy (e-maile):</info>');
            foreach ($emails as $email) {
                $output->writeln($email);
            }
        }
        return Command::SUCCESS;
    }
}
