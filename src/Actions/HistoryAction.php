<?php

namespace Dersonsena\Migrations\Actions;

use Dersonsena\Migrations\PDOConnection;

class HistoryAction implements ActionInterface
{
    /**
     * @var PDOConnection
     */
    private $connection;

    public function __construct(PDOConnection $connection)
    {
        $this->connection = $connection;
    }

    public function execute()
    {
        $migrationsDb = $this->getMigrationsHistory();

        echo 'Showing the last 10 applied migrations:' . PHP_EOL;
        
        foreach ($migrationsDb as $migration) {
            echo "\t({$migration['timestamp']}) {$migration['migration']}" . PHP_EOL;
        }
    }

    /**
     * @return array
     */
    private function getMigrationsHistory(): array
    {
        $sql = "
            SELECT `timestamp`, `migration`
            FROM `". MIGRATION_TABLENAME ."`
            ORDER BY `timestamp` DESC
            LIMIT 10
        ";

        return $this->connection->fetchAll($sql);
    }
}
