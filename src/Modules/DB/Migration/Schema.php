<?php


namespace Freedom\Modules\DB\Migration;


use Freedom\Modules\DB\Connection;

final class Schema
{
    public static function make(string $name, callable $boot)
    {
        echo "Start migrating {$name} table..." . "\n";
        $table = new Master();
        $boot($table);
        $columns = '';
        $keys = '';
        foreach ($table->columns as $key => $column) {
            $args = explode('#', $column->getSignatureSQL());
            if (count($args) >= 2) {
                $keys .= trim($args[1]) . "\n";
            }

            $columns .= trim($args[0]) . "\n";
        }

        $columns = trim(preg_replace('/\n/', ",\n ", $columns));
        $keys = trim(preg_replace('/\n/', ",\n ", $keys));

        // cut the comma at the end of string
        if (strlen($keys) === 0 && substr($columns, strlen($columns) - strlen(',')) === ',') {
            $columns = substr($columns, 0, strlen($columns) - strlen(','));
        }

        if (substr($keys, strlen($keys) - strlen(',')) === ',') {
            $keys = substr($keys, 0, strlen($keys) - strlen(','));
        }

        $fields = "{$columns} {$keys}";
        $sql = "CREATE TABLE {$name} (\n{$fields}\n)";

        $connection = Connection::getInstance();
        $connection->getConnection()
            ->prepare($sql)
            ->execute();

        $now = date('Y-m-d H:i:s', time());
        $connection->getConnection()
            ->prepare(
                "INSERT INTO migrations (name, created_at, updated_at) VALUES ('{$name}', '{$now}', '{$now}')"
            )
            ->execute();
        echo "Table {$name} created successfully!" . "\n";
    }

    public static function makeIfNotExists(string $name, callable $boot)
    {
        $database = env('DB_NAME');
        $connection = Connection::getInstance();
        $statement = $connection->getConnection()
            ->prepare("SHOW TABLES FROM {$database} LIKE '{$name}';");
        $statement->execute();
        $exists = !empty($statement->fetchAll());
        if (!$exists) {
            self::make($name, $boot);
        } else {
            echo "Table {$name} Already exists" . "\n";
        }
    }

    public static function table(string $name, callable $boot)
    {
        // update fields
    }

    public static function dropIfExists(string $name)
    {
        // DROP if exists
    }

    public static function drop(string $name)
    {
        // DROP
    }
}
