<?php


namespace Freedom\Modules\DB;


use Freedom\Modules\DB\Builder\Query;

abstract class Model extends Query
{
    protected static string $table = '';
    protected function getTable(): string {
        return static::$table;
    }
}
