<?php

namespace Dersonsena\Migrations\Migrations;

class Kilgration1559840589 extends \Dersonsena\Migrations\MigrationAbstract
{
    public function upgrade()
    {
        $this->connection->addColumn('persons', 'birth_date', 'DATE DEFAULT NULL');
        $this->connection->addColumn('persons', 'cpf', 'VARCHAR(11) UNIQUE');
    }

    public function downgrade()
    {
        $this->connection->dropColumn('persons', 'birth_date');
        $this->connection->dropColumn('persons', 'cpf');
    }
}
