<?php


namespace Freedom\Modules\DB;


use Freedom\Modules\DB\Builder\QueryBuilder;
use Freedom\Modules\DB\Traits\ConnectionResolverable;

abstract class Model
{
    use ConnectionResolverable;

    protected string $table = '';
    protected string $connectionName = 'default';

    public function getTable(): string
    {
        return $this->table;
    }

    public function newQuery(): QueryBuilder
    {
        return new QueryBuilder($this, $this->getConnection());
    }
}
