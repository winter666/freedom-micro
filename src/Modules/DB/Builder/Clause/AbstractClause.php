<?php


namespace Freedom\Modules\DB\Builder\Clause;


abstract class AbstractClause
{
    abstract public function push(...$args);
    abstract public function run();
}
