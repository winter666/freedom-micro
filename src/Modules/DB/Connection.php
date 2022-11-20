<?php


namespace Freedom\Modules\DB;

use Freedom\Modules\DB\Exceptions\DBConnectException;
use Freedom\Modules\TargetInterface;

class Connection implements TargetInterface
{
    private \PDO $connection;

    public function __construct(
        private string $db,
        private string $host,
        private string $db_name,
        private string $username,
        private string $password
    ) {
        $this->setConnection();
    }

    public function getConnection(): \PDO {
        return $this->connection;
    }

    private function setConnection() {
        try {
            $this->connection = new \PDO($this->db . ':host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
        } catch (\PDOException $e) {
            throw new DBConnectException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }
    }
}
