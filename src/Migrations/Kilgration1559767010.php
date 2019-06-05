<?php

namespace Dersonsena\Migrations\Migrations;

class Kilgration1559767010 extends \Dersonsena\Migrations\MigrationAbstract
{
    public function upgrade()
    {
        /* Equivalente a: DROP TABLE IF EXISTS `persons`*/
        $this->connection->dropTable('persons');
    }

    public function downgrade()
    {
    }
}
