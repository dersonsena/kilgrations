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
                return new AddMigrationAction($connection);
            case 2:
                return new UpgradeAction($connection);
            case 3:
                return new DowngradeAction($connection);
            case 4:
                return new HistoryAction($connection);
            case 5:
                return new ExitAction;
        }
    }
}
