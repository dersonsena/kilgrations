<?php

namespace Dersonsena\Migrations;

use Dersonsena\Migrations\PDOConnection;

abstract class MigrationAbstract
{
    /**
     * @var PDOConnection
     */
    protected $connection;

    public function __construct(PDOConnection $connection)
    {
        $this->connection = $connection;
    }

    abstract public function upgrade();

    abstract public function downgrade();

    /**
     * @param string $tableName
     * @param array $fields
     * @return void
     */
    public function dropTable(string $tableName)
    {
        $sql = sprintf("DROP TABLE IF EXISTS `%s`", $tableName);
        return $this->connection->execute($sql);
    }

    /**
     * @param string $tableName
     * @param string $column
     * @param string $type
     * @return void
     */
    public function addColumn(string $tableName, string $column, string $type)
    {
        $sql = sprintf("ALTER TABLE `%s` ADD COLUMN `%s` %s", $tableName, $column, $type);
        return $this->connection->execute($sql);
    }

    /**
     * @param string $tableName
     * @param string $column
     * @return void
     */
    public function dropColumn(string $tableName, string $column)
    {
        $sql = sprintf("ALTER TABLE `%s` DROP COLUMN `%s`", $tableName, $column);
        return $this->connection->execute($sql);
    }
}
