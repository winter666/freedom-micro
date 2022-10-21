<?php


namespace Winter666\Freedom\Modules\DB\Builder\Clause;


class DeleteClause extends AbstractClause
{
    private string $table;

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    public function push(...$args) {}

    public function run(): string
    {
        return "DELETE FROM {$this->table}";
    }
}
