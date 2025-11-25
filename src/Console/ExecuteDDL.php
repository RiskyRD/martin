<?php

namespace App\Console;

use Core\Database\DB;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;

#[AsCommand(
    name: 'execute:ddl',
    description: 'Execute DDL commands on the database.',
)]
class ExecuteDDL extends Command
{
    protected DB $db;
    public function __construct(DB $db)
    {
        parent::__construct();
        $this->db = $db;
    }

    public function __invoke()
    {
        echo "Executing DDL commands...\n";

        $sql = file_get_contents(__DIR__ . '/../../db/ddl/up.sql');
        $this->db->getConnection()->exec($sql);

        echo "DDL commands executed successfully.\n";
        return Command::SUCCESS;
    }
}
