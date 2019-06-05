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
}
