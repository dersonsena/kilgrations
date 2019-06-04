<?php
use Dersonsena\Migrations\PDOConnection;
use Dersonsena\Migrations\MigrationApp;

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

if (!in_array($choise, [1, 2, 3, 4, 5])) {
    echo 'Invalid Option. Operation Aborted!' . PHP_EOL;
    exit(0);
}

if ($choise === 5) {
    echo 'See ya! ;)' . PHP_EOL;
    exit(0);
}

(new MigrationApp($pdo, $choise))->run();
