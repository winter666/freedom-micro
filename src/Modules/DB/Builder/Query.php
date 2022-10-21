<?php


namespace Winter666\Freedom\Modules\DB\Builder;



abstract class Query
{
    protected static string $table;
    protected static QueryBuilder $query;

    public function newQuery(): QueryBuilder
    {
        return static::query();
    }

    public static function query(): QueryBuilder
    {
        return static::$query = new QueryBuilder(['table' => static::$table, 'model' => static::class]);
    }
}
