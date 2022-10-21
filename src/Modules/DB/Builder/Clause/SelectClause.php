<?php


namespace Winter666\Freedom\Modules\DB\Builder\Clause;


class SelectClause extends AbstractClause
{
    private const SELECT = 'SELECT';
    private const FROM = 'FROM';
    private const ALL = '*';
    private string $fields;
    private string $table;

    public function __construct(string $table)
    {
        $this->fields = static::ALL;
        $this->table = $table;
    }

    public function push(...$args)
    {
        $fields = [];
        if (is_null($args)) {
            $fields = [static::ALL];
        } elseif (is_array($args)) {
            $fields = $args[0];
            if (empty($args)) {
                $fields = [static::ALL];
            }
        }

        $strFields = implode(',', $fields);
        $this->fields = $strFields;
    }

    public function run(): string
    {
        return static::SELECT . ' ' . $this->fields . ' ' . static::FROM . ' ' . $this->table;
    }
}
