<?php


namespace Freedom\Modules\DB\Traits;


use Freedom\Modules\DB\Connection;
use Freedom\Modules\DB\ConnectionResolver;

trait ConnectionResolverable
{
    protected static ConnectionResolver $connectionResolver;

    public function getConnectionName(): string
    {
        return $this->connectionName;
    }

    public static function setConnectionResolver(ConnectionResolver $instance)
    {
        static::$connectionResolver = $instance;
    }

    public function getConnection(): Connection
    {
        return static::$connectionResolver->resolve($this->getConnectionName());
    }
}
