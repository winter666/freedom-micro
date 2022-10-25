<?php


namespace Freedom\Modules\DB\Builder\Clause;


class CreateClause
{
    private string $table;
    private array $fields;
    private array $values;
    private string $prepare = '';

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    public function push(...$args) {
        $this->fields = array_keys($args[0]);
        $this->values = array_values($args[0]);
    }

    public function run()
    {
        $strFields = implode(',', $this->fields);
        $strValues = implode(',', array_map(fn ($val) => ('?'), $this->values));
        $query = "INSERT INTO {$this->table} ({$strFields}) VALUES ({$strValues})";
        $this->prepare = $query;
    }

    public function getPrepare(): string
    {
        return $this->prepare;
    }

    public function getValue(): array
    {
        return $this->values;
    }
}
