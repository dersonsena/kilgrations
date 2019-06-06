<?php

namespace Dersonsena\Migrations\Actions;

trait ActionHelperTrait
{
    /**
     * @param array $classList
     * @return array
     */
    private function getMigrationsClassMap(array $classList): array
    {
        return array_map(function ($className) {
            $fullClassName = "Dersonsena\\Migrations\\Migrations\\{$className}";
            $obj = new $fullClassName($this->connection);

            return ['name' => $className, 'object' => $obj];
        }, $classList);
    }

    /**
     * @param integer $limit
     * @return array
     */
    private function getMigrationsOfTheDb(int $limit = null): array
    {
        $sql = "SELECT `migration`, `migration` FROM `". MIGRATION_TABLENAME ."` ORDER BY `timestamp` DESC";

        if (!is_null($limit)) {
            $sql .= " LIMIT {$limit}";
        }

        $rows = $this->connection->fetchAll($sql);

        return array_map(function ($migration) {
            return $migration['migration'];
        }, $rows);
    }

    /**
     * @return array
     */
    private function getMigrationFiles(): array
    {
        $migrationsPath = scandir(MIGRATION_DIR);

        $migrationsPath = array_filter($migrationsPath, function ($file) {
            return !in_array($file, ['.', '..', '.gitkeep']);
        });

        return array_map(function ($file) {
            return str_replace('.php', '', $file);
        }, $migrationsPath);
    }
}
