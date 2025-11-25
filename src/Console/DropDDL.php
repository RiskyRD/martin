<?php

namespace App\Console;

use Core\Database\DB;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;

#[AsCommand(
    name: 'drop:ddl',
    description: 'Drop DDL commands on the database.',
)]
class DropDDL extends Command
{
    protected DB $db;
    public function __construct(DB $db)
    {
        parent::__construct();
        $this->db = $db;
    }

    public function __invoke()
    {
        echo "Dropping DDL commands...\n";
        $sql = file_get_contents(__DIR__ . '/../../db/ddl/down.sql');
        $this->db->getConnection()->exec($sql);
        echo "DDL commands dropped successfully.\n";
        return Command::SUCCESS;
    }
}
