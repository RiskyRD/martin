<?php

namespace App\Console;

use App\Model\UserModel;
use Core\Database\DB;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'add:admin',
    description: 'Add a new admin user.',
)]
class AddAdmin extends Command
{
    protected UserModel $userModel;
    public function __construct(UserModel $userModel)
    {
        parent::__construct();
        $this->userModel = $userModel;
    }

    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Name of the admin user')
            ->addArgument('email', InputArgument::REQUIRED, 'Email of the admin user')
            ->addArgument('password', InputArgument::REQUIRED, 'Password of the admin user');
    }
    public function __invoke(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        if (!$this->userModel->isEmailUnique($email)) {
            $output->writeln("Error: Email '$email' is already in use.");
            return Command::FAILURE;
        }
        $this->userModel->saveUser([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'isAdmin' => true,
        ]);
        return Command::SUCCESS;
    }
}
