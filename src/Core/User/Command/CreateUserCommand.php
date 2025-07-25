<?php

namespace App\Core\User\Command;

use App\Core\User\Domain\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Common\Mailer\MailerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:user:create',
    description: 'Create a new user with given email (inactive by default).',
)]
class CreateUserCommand extends Command
{
    private EntityManagerInterface $em;
    private MailerInterface $mailer;

    public function __construct(EntityManagerInterface $em, MailerInterface $mailer)
    {
        parent::__construct();
        $this->em = $em;
        $this->mailer = $mailer;
    }

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED, 'User email');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument('email');
        $user = new User($email);
        $this->em->persist($user);
        $this->em->flush();

        $this->mailer->send(
            $user->getEmail(),
            'Rejestracja konta',
            'Zarejestrowano konto w systemie. Aktywacja konta trwa do 24h'
        );

        $output->writeln('Użytkownik został utworzony.');
        return Command::SUCCESS;
    }
}
