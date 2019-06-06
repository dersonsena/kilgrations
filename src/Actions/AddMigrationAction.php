<?php

namespace Dersonsena\Migrations\Actions;

use Dersonsena\Migrations\PDOConnection;

class AddMigrationAction implements ActionInterface
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
        $className = 'Kilgration' . time();

        $data = <<<EOT
<?php

namespace Dersonsena\\Migrations\\Migrations;

class {$className} extends \Dersonsena\\Migrations\\MigrationAbstract
{
    public function upgrade()
    {
    }

    public function downgrade()
    {
    }
}\n

EOT;

        if (!is_writeable(MIGRATION_DIR)) {
            echo "The migration dir '". MIGRATION_DIR ."' is not writable." . PHP_EOL;
            return;
        }

        $handle = fopen(MIGRATION_DIR . DS . $className . '.php', 'w');
        fwrite($handle, trim($data));

        echo "The migration {$className} has been created successfully." . PHP_EOL;
    }
}
