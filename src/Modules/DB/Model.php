<?php


namespace Freedom\Modules\DB;


use Freedom\Modules\DB\Builder\QueryBuilder;

abstract class Model
{
    protected string $table = '';
    protected string $connectionName = 'default';
    protected static ConnectionResolver $connectionResolver;

    public function getTable(): string {
        return $this->table;
    }

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

    public function newQuery(): QueryBuilder
    {
        return new QueryBuilder($this, $this->getConnection());
    }
}
