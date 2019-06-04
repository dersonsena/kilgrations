<?php

namespace Dersonsena\Migrations\Actions;

use Dersonsena\Migrations\PDOConnection;

class ActionFactory
{
    /**
     * @param integer $choise
     * @return ActionInterface
     */
    public static function build(int $choise, PDOConnection $connection): ActionInterface
    {
        switch ($choise) {
            case 1:
                return new AddMigration($connection);
            case 2:
                return new Upgrade($connection);
            case 3:
                return new Downgrade($connection);
            case 4:
                return new History($connection);
        }
    }
}
