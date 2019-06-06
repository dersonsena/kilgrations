<?php

namespace Dersonsena\Migrations;

use Dersonsena\Migrations\Actions\ActionFactory;

class MigrationApp
{
    /**
     * @var PDOConnection
     */
    private $connection;

    /**
     * @var int
     */
    private $choise;

    /**
     * @var int
     */
    private $downgradeCount;

    public function __construct(PDOConnection $connection, int $choise, int $downgradeCount = 1)
    {
        $this->connection = $connection;
        $this->choise = $choise;
        $this->downgradeCount = $downgradeCount;
    }

    /**
     * @return void
     */
    public function run()
    {
        $this->createMigrationTable();

        $action = ActionFactory::build($this->connection, $this->choise, $this->downgradeCount);
        $action->execute();
        exit(0);
    }

    /**
     * @return void
     */
    public function createMigrationTable()
    {
        if ($this->connection->tableExists(MIGRATION_TABLENAME)) {
            return;
        }

        $this->connection->createTable(MIGRATION_TABLENAME, [
            'id' => 'INT NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'timestamp' => 'TIMESTAMP NOT NULL',
            'migration' => 'VARCHAR(100) UNIQUE NOT NULL'
        ]);
    }
}
