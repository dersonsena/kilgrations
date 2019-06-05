<?php

namespace Dersonsena\Migrations\Actions;

use Dersonsena\Migrations\PDOConnection;
use Dersonsena\Migrations\MigrationAbstract;

class UpgradeAction implements ActionInterface
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

        $output = '';
        $migrationsDb = $this->connection->fetchAll($sql);
        $migrationsPath = $this->getMigrationFiles();
        $intercect = array_diff($migrationsPath, $migrationsDb);

        /** @var MigrationAbstract[] $migrationsMap */
        $migrationsMap = array_map(function ($className) use ($output) {
            $output = $className . PHP_EOL;
            $fullClassName = "Dersonsena\\Migrations\\Migrations\\{$className}";
            return new $fullClassName($this->connection);
        }, $intercect);

        foreach ($migrationsMap as $migration) {
            $migration->upgrade();
        }

        $output = 'The following migration(s) were executed successfully.' . $output;
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
