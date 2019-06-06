<?php

use Dersonsena\Migrations\PDOConnection;
use Dersonsena\Migrations\MigrationApp;

date_default_timezone_set("America/Fortaleza");

require_once __DIR__ . '/vendor/autoload.php';

define('DS', DIRECTORY_SEPARATOR);
define('MIGRATION_DIR', __DIR__ . DS . 'src' . DS . 'Migrations');
define('MIGRATION_TABLENAME', 'migrations');

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

$pdo = PDOConnection::build([
    'dsn' => getenv('DB_DSN'),
    'username' => getenv('DB_USERNAME'),
    'password' => getenv('DB_PASSWORD'),
    'charset' => getenv('DB_CHARSET')
]);

$message  = "
==========================================
KILGRATION v1.0
==========================================
Choose one of the options below:

(1) Create a new migration
(2) UPgrade database
(3) DOWNgrade database
(4) Show Migration History
(5) Exit
==========================================\n";

echo $message;

$choise = (int)readline('Your Choise: ');
$downgradeCount = 1;

if (!in_array($choise, [1, 2, 3, 4, 5])) {
    echo 'Invalid Option. Operation Aborted!' . PHP_EOL;
    exit(0);
}

if ($choise === 3) {
    $downgradeCount = PHP_EOL . (int)readline('How many steps do you want downgrade? (default = 1): ');
}

(new MigrationApp($pdo, $choise, $downgradeCount))->run();
