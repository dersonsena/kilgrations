<?php

namespace Dersonsena\Migrations\Actions;

use Dersonsena\Migrations\PDOConnection;

class DowngradeAction implements ActionInterface
{
    /**
     * @var PDOConnection
     */
    private $connection;

    public function __construct(PDOConnection $connection)
    {
        $this->connection = $connection;
    }

    public function execute()
    {
        echo 'Downgrade Action';
    }
}
