<?php

namespace Dersonsena\Migrations\Actions;

use Dersonsena\Migrations\PDOConnection;

class UpgradeAction implements ActionInterface
{
    use ActionHelperTrait;

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
        $migrationsDb = $this->getMigrationsOfTheDb();
        $migrationsPath = $this->getMigrationFiles();
        $intercect = array_diff($migrationsPath, $migrationsDb);

        if (empty($intercect)) {
            echo 'No migration will be performed. Your database structure is up to date.' . PHP_EOL;
            return;
        }

        $migrationsMap = $this->getMigrationsClassMap($intercect);

        foreach ($migrationsMap as $migration) {
            $migration['object']->upgrade();
            sleep(0.5);

            $this->connection->insert(MIGRATION_TABLENAME, [
                'timestamp' => date('Y-m-d H:i:s'),
                'migration' => $migration['name']
            ]);

            echo '>> Applying the migration ' . $migration['name'] . PHP_EOL;
        }

        echo 'The following migration(s) were executed successfully.' . PHP_EOL;
    }
}
