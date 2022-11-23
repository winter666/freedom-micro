<?php


namespace Freedom\Modules\DB\Migration;


use Freedom\Modules\DB\Traits\ConnectionResolverable;

final class Schema
{
    use ConnectionResolverable;

    protected string $connectionName = 'default';

    public static function make(string $name, callable $boot)
    {
        echo "Start migrating {$name} table..." . "\n";
        (new Schema())->createTable($name, $boot);
        echo "Table {$name} created successfully!" . "\n";
    }

    public function createTable(string $name, callable $boot)
    {
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
        $connection = $this->getConnection();
        $connection->getConnection()
            ->prepare($sql)
            ->execute();

        $now = date('Y-m-d H:i:s', time());
        $connection->getConnection()
            ->prepare(
                "INSERT INTO migrations (name, created_at, updated_at) VALUES ('{$name}', '{$now}', '{$now}')"
            )
            ->execute();
    }

    public function isExistsTable(string $name): bool
    {
        $database = env('DB_NAME');
        $connection = $this->getConnection();
        $statement = $connection->getConnection()
            ->prepare("SHOW TABLES FROM {$database} LIKE '{$name}';");
        $statement->execute();
        return !empty($statement->fetchAll());
    }

    public static function makeIfNotExists(string $name, callable $boot)
    {
        $exists = (new Schema())->isExistsTable($name);
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
        echo "Start rolling-back {$name}";
        (new Schema())->drop($name, true);
        echo "Rolling-back {$name} successful";
    }

    protected function drop(string $name, bool $ifExists = false) {
        $sql = 'DROP TABLE' . ($ifExists ? ' IF EXISTS ': ' ') . "{$name};";
        $connection = $this->getConnection();
        $connection->getConnection()
            ->prepare($sql)
            ->execute();
    }
}
