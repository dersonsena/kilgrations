<?php

namespace Dersonsena\Migrations;

class PDOConnection
{
    /**
     * @var \PDO
     */
    private static $connection;

    /**
     * @var string
     */
    private $databaseName;

    private function __construct(array $params)
    {
        $partialDsn = explode(';', $params['dsn']);
        $this->databaseName = explode('=', $partialDsn[2])[1];

        try {
            $options = [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            ];

            static::$connection = new \PDO(
                $params['dsn'],
                $params['username'],
                $params['password'],
                $options
            );
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }

    /**
     * @return PDOConnection
     */
    public static function build(array $params): PDOConnection
    {
        return new static($params);
    }

    /**
     * @param string $sql
     * @return void
     */
    public function execute(string $sql)
    {
        return static::$connection
            ->prepare($sql)
            ->execute();
    }

    /**
     * @param string $sql
     * @return void
     */
    public function query(string $sql)
    {
        return static::$connection->query($sql);
    }

    /**
     * @param string $sql
     * @return void
     */
    public function exec(string $sql)
    {
        return static::$connection->exec($sql);
    }

    /**
     * @param string $tableName
     * @param array $data
     * @return void
     */
    public function insert(string $tableName, array $data)
    {
        $columns = array_keys($data);

        $columnsBind = array_map(function ($column) {
            return ':' . $column;
        }, $columns);

        $columns = implode(', ', $columns);
        $columnsBind = implode(', ', $columnsBind);
        $sql = sprintf('INSERT INTO `%s` (%s) VALUES (%s)', $tableName, $columns, $columnsBind);

        return static::$connection
            ->prepare($sql)
            ->execute($data);
    }

    /**
     * @param string $tableName
     * @param string $conditions
     * @return void
     */
    public function delete(string $tableName, string $conditions)
    {
        $sql = sprintf('DELETE FROM `%s` WHERE (%s)', $tableName, $conditions);

        return static::$connection
            ->prepare($sql)
            ->execute();
    }

    /**
     * @param string $sql
     * @param array $params
     * @return void
     */
    public function fetchOne(string $sql, array $params = [])
    {
        $stmt = static::$connection->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetch();
    }

    /**
     * @param string $sql
     * @param array $params
     * @return array
     */
    public function fetchAll(string $sql, array $params = []): array
    {
        $stmt = static::$connection->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    /**
     * @param string $tableName
     * @param array $fields
     * @return void
     */
    public function createTable(string $tableName, array $fields)
    {
        $rawFields = array_map(function ($type, $name) {
            return "`{$name}` {$type}";
        }, $fields, array_keys($fields));

        $rawFields = implode(",\n", $rawFields);

        $sql = sprintf(
            "CREATE TABLE IF NOT EXISTS `%s` (%s) DEFAULT CHARACTER SET utf8 ENGINE=InnoDB",
            $tableName,
            $rawFields
        );

        return $this->execute($sql);
    }

    /**
     * @param string $tableName
     * @param array $fields
     * @return void
     */
    public function dropTable(string $tableName)
    {
        $sql = sprintf("DROP TABLE IF EXISTS `%s`", $tableName);
        return $this->execute($sql);
    }

    /**
     * @param string $tableName
     * @return bool
     */
    public function tableExists(string $tableName): bool
    {
        $sql = "
            SELECT TABLE_NAME FROM information_schema.tables
            WHERE table_schema = :schemaName AND table_name = :tableName
            LIMIT 1;
        ";

        $row = $this->fetchOne($sql, [
            ':schemaName' => $this->databaseName,
            ':tableName' => $tableName
        ]);

        return $row !== false;
    }

    /**
     * @param string $tableName
     * @param string $column
     * @param string $type
     * @return void
     */
    public function addColumn(string $tableName, string $column, string $type)
    {
        $sql = sprintf("ALTER TABLE `%s` ADD COLUMN `%s` %s", $tableName, $column, $type);
        return $this->execute($sql);
    }

    /**
     * @param string $tableName
     * @param string $column
     * @return void
     */
    public function dropColumn(string $tableName, string $column)
    {
        $sql = sprintf("ALTER TABLE `%s` DROP COLUMN `%s`", $tableName, $column);
        return $this->execute($sql);
    }
}
