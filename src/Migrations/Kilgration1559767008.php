<?php

namespace Dersonsena\Migrations\Migrations;

class Kilgration1559767008 extends \Dersonsena\Migrations\MigrationAbstract
{
    public function upgrade()
    {
        /** Equivalente a: ALTER TABLE `persons` DROP COLUMN `name` */
        $this->connection->dropColumn('persons', 'name');
    }

    public function downgrade()
    {
        $this->connection->addColumn('persons', 'name', 'VARCHAR(60) NOT NULL');
    }
}
