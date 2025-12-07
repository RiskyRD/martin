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

        $config = require __DIR__ . '/../../config/db.php';
        $dbname = $config['database'];

        $conn = $this->db->getConnection();

        $conn->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");

        $conn->exec("USE `$dbname`");

        $sql = file_get_contents(__DIR__ . '/../../db/ddl/down.sql');
        $conn->exec($sql);

        echo "DDL commands dropped successfully.\n";
        return Command::SUCCESS;
    }
}