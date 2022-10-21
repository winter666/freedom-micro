<?php


namespace Winter666\Freedom\Modules\DB;

use Winter666\Freedom\Modules\DB\Exceptions\DBConnectException;
use PDO;

class Connection
{
    private static Connection|null $instance = null;
    private PDO $connection;
    public string $connection_id;
    public array $connections;
    private string $db;
    private string $host;
    private string $db_name;
    private string $username;
    private string $password;

    private function __construct() {
        $this->db = (string) env('DB');
        $this->host = (string) env('DB_HOST');
        $this->db_name = (string) env('DB_NAME');
        $this->username = (string) env('DB_USERNAME');
        $this->password = (string) env('DB_PASSWORD');

        $this->setConnection();
    }

    public static function getInstance(): static {
        if (static::$instance === null) {
            static::$instance = new Connection();
        }

        return static::$instance;
    }

    public function getConnection(): PDO {
        return $this->connection;
    }

    private function setConnection() {
        try {
            $this->connection = new PDO($this->db . ':host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
        } catch (\PDOException $e) {
            throw new DBConnectException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }

        $this->connection_id = rand(1000, 9999);
        $this->connections[$this->connection_id] = $this->connection;
    }
}
