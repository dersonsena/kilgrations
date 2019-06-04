<?php

namespace Dersonsena\Migrations\Actions;

use Dersonsena\Migrations\PDOConnection;

class Upgrade implements ActionInterface
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
        $sql = "SELECT `migration` FROM `". MIGRATION_TABLENAME ."` ORDER BY `timestamp` DESC";

        $migrationsDb = $this->connection->fetchAll($sql);
        $migrationsPath = $this->getMigrationFiles();
        $intercect = array_diff($migrationsPath, $migrationsDb);

        print_r($intercect); die;
    }

    private function getMigrationFiles(): array
    {
        $migrationsPath = scandir(MIGRATION_DIR);

        $migrationsPath = array_filter($migrationsPath, function ($file) {
            return !in_array($file, ['.', '..', '.gitkeep']);
        });

        return array_map(function ($file) {
            return str_replace('.php', '', $file);
        }, $migrationsPath);
    }
}
