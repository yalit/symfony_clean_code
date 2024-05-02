<?php

namespace App\Application\Command\User;

use App\Domain\User\Action\CreateUserInput;
use App\Domain\User\Model\Enum\UserRole;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:user:create-first-admin',
    description: 'Command to create the first admin in the application when no user are present.',
)]
class AdminCreateFirstCommand extends Command
{
    public function __construct(private readonly MessageBusInterface $messageBus)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::OPTIONAL, 'Name of the admin')
            ->addArgument('email', InputArgument::OPTIONAL, 'Email of the admin')
            ->addArgument('password', InputArgument::OPTIONAL, 'Password of the admin')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
       $this->verifyArgumentPresent($input, $output, 'name');
       $this->verifyArgumentPresent($input, $output, 'email');
       $this->verifyArgumentPresent($input, $output, 'password');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $name = $input->getArgument('name');
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        $input = new CreateUserInput($name, $email, $password, UserRole::ADMIN);
        try{
            $this->messageBus->dispatch($input);
        } catch (\Exception $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }

        $io->success(sprintf('Admin created successfully. Name: %s, Email: %s', $name, $email));
        return Command::SUCCESS;
    }

    private function verifyArgumentPresent(InputInterface $input, OutputInterface $output, string $argument): void
    {
        if ($input->getArgument($argument)) {
            return;
        }

        $question = new Question(sprintf("%s should be defined. Please enter the correct value : ", $argument));
        $value = $this->getHelper('question')->ask($input, $output, $question);

        while (empty($value)) {
            $output->writeln("Value cannot be empty");
            $value = $this->getHelper('question')->ask($input, $output, $question);
        }

        $input->setArgument($argument, $value);
    }
}
