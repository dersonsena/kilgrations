<?php

namespace Dersonsena\Migrations\Migrations;

class Kilgration1559840654 extends \Dersonsena\Migrations\MigrationAbstract
{
    public function upgrade()
    {
        $this->connection->addColumn('persons', 'last_name', 'VARCHAR(60) AFTER `name`');
    }

    public function downgrade()
    {
        $this->connection->dropColumn('persons', 'last_name');
    }
}
