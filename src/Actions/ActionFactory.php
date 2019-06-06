<?php

namespace Dersonsena\Migrations\Actions;

use Dersonsena\Migrations\PDOConnection;

class ActionFactory
{
    /**
     * @param PDOConnection $connection
     * @param integer $choise
     * @param integer $downgradeCount
     * @return ActionInterface
     */
    public static function build(PDOConnection $connection, int $choise, int $downgradeCount): ActionInterface
    {
        switch ($choise) {
            case 1:
                return new AddMigrationAction($connection);
            case 2:
                return new UpgradeAction($connection);
            case 3:
                return new DowngradeAction($connection, $downgradeCount);
            case 4:
                return new HistoryAction($connection);
            case 5:
                return new ExitAction;
        }
    }
}
