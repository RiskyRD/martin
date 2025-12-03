<?php

namespace App\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'asset:symlink',
    description: 'Create a symbolic link for assets.',
)]
class AssetSymlink extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __invoke(OutputInterface $output)
    {
        $output->writeln("Creating symbolic link for assets...\n");
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $cmd = 'mklink /J ' . '"' . __DIR__ . '/../../public/assets' . '" "' . realpath(__DIR__ . '/../../assets') . '"';
            $output->writeln("Executing command: $cmd\n");
            exec($cmd);
        } else {
            symlink(realpath(__DIR__ . '/../../assets'), realpath(__DIR__ . '/../../public/assets'));
        }
        $output->writeln("Symbolic link created successfully.\n");
        return Command::SUCCESS;
    }
}
