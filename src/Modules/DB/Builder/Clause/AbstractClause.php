<?php


namespace Winter666\Freedom\Modules\DB\Builder\Clause;


abstract class AbstractClause
{
    abstract public function push(...$args);
    abstract public function run();
}
