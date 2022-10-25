<?php


namespace Freedom\Modules\DB\Builder\Clause;


class WhereClause extends AbstractClause
{
    private array $wheres = [];
    private string $prepare = '';
    private array $values = [];

    public function push(...$args)
    {
        $field = $args[0];
        $value = $args[1];
        $operator = $args[2];
        $expression = $args[3];
        $this->wheres[] = compact('field', 'value', 'operator', 'expression');
    }

    public function run()
    {
        if (!empty($this->wheres)) {
            foreach ($this->wheres as $key => $whereItem) {
                if ($key === 0) {
                    $prefix = " WHERE";
                } else {
                    $prefix = " " . $whereItem['expression'];
                }

                $this->prepare .= $prefix . " " . $whereItem['field'] . " " . $whereItem['operator'] . " ?";
                $this->values[] = $whereItem['value'];
            }
        }
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
