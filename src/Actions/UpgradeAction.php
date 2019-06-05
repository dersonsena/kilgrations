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
        $output = '';
        $migrationsDb = $this->getMigrationsOfTheDb();
        $migrationsPath = $this->getMigrationFiles();
        $intercect = array_diff($migrationsPath, $migrationsDb);

        if (empty($intercect)) {
            echo 'No migration will be performed. Your database structure is up to date.' . PHP_EOL;
            exit(0);
        }

        /** @var MigrationAbstract[] $migrationsMap */
        $migrationsMap = array_map(function ($className) use ($output) {
            $fullClassName = "Dersonsena\\Migrations\\Migrations\\{$className}";
            $obj = new $fullClassName($this->connection);

            return ['name' => $className, 'object' => $obj];
        }, $intercect);

        foreach ($migrationsMap as $migration) {
            $migration['object']->upgrade();

            $this->connection->insert(MIGRATION_TABLENAME, [
                'timestamp' => date('Y-m-d H:i:s'),
                'migration' => $migration['name']
            ]);

            $output .= '>> ' . $migration['name'] . PHP_EOL;
        }

        echo 'The following migration(s) were executed successfully.' . PHP_EOL . $output;
    }

    /**
     * @return array
     */
    private function getMigrationsOfTheDb(): array
    {
        $sql = "SELECT `migration` FROM `". MIGRATION_TABLENAME ."` ORDER BY `timestamp` DESC";
        $rows = $this->connection->fetchAll($sql);

        return array_map(function ($migration) {
            return $migration['migration'];
        }, $rows);
    }

    /**
     * @return array
     */
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
