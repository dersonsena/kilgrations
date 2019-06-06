<?php

namespace Dersonsena\Migrations\Migrations;

class Kilgration1559767005 extends \Dersonsena\Migrations\MigrationAbstract
{
    public function upgrade()
    {
        /** Equivalente a: ALTER TABLE `persons` ADD COLUMN `birth_date` DATE DEFAULT NULL */
        $this->connection->addColumn('persons', 'birth_date', 'DATE DEFAULT NULL');
    }

    public function downgrade()
    {
        $this->connection->dropColumn('persons', 'birth_date');
    }
}
