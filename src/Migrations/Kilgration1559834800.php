<?php

namespace Dersonsena\Migrations\Migrations;

class Kilgration1559834800 extends \Dersonsena\Migrations\MigrationAbstract
{
    public function upgrade()
    {
        $this->connection->createTable('persons', [
            'id' => 'INT NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'name' => 'VARCHAR(60) NOT NULL',
            'email' => 'VARCHAR(60) NOT NULL'
        ]);
    }

    public function downgrade()
    {
        $this->connection->dropTable('persons');
    }
}
