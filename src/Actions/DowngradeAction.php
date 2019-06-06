<?php

namespace Dersonsena\Migrations\Actions;

use Dersonsena\Migrations\PDOConnection;

class DowngradeAction implements ActionInterface
{
    use ActionHelperTrait;

    /**
     * @var PDOConnection
     */
    private $connection;

    /**
     * @var int
     */
    private $downgradeCount;

    public function __construct(PDOConnection $connection, int $downgradeCount)
    {
        $this->connection = $connection;
        $this->downgradeCount = $downgradeCount;
    }

    public function execute()
    {
        $migrationsDb = $this->getMigrationsOfTheDb();
        $totalMigrations = count($migrationsDb);

        if ($totalMigrations === 0) {
            echo 'No migration will be reverted.' . PHP_EOL;
            return;
        }

        $count = ($this->downgradeCount > $totalMigrations ? $totalMigrations : $this->downgradeCount);
        $migrationsMap = $this->getMigrationsClassMap($migrationsDb);

        for ($i = 0; $i < $count; $i++) {
            $migrationsMap[$i]['object']->downgrade();
            $this->connection->delete(MIGRATION_TABLENAME, "`migration` = '{$migrationsMap[$i]['name']}'");
            
            echo '>> Reverting the migration ' . $migrationsDb[$i] . PHP_EOL;
        }
        
        echo 'The following migration(s) were reverted successfully.' . PHP_EOL;
    }
}
