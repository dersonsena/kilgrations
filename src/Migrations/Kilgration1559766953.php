<?php

namespace Dersonsena\Migrations\Migrations;

class Kilgration1559766953 extends \Dersonsena\Migrations\MigrationAbstract
{
    public function upgrade()
    {
        /* Equivalente a:
        CREATE TABLE IF NOT EXISTS `persons` (
            `id` INT NOT NULL PRIMARY KEY,
            `name` VARCHAR(60) NOT NULL,
            `email` VARCHAR(60) NOT NULL
        )*/
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
