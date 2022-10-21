<?php


namespace Winter666\Freedom\Modules\DB;


use Winter666\Freedom\Modules\DB\Builder\Query;

abstract class Model extends Query
{
    protected static string $table = '';
    protected function getTable(): string {
        return static::$table;
    }
}
