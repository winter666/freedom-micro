<?php


namespace Freedom\Modules\DB\Migration;


class Master
{
    public array $columns = [];

    public function id()
    {
        $column = new ColumnClause('id', ColumnClause::TYPE_INT);
        $column->auto_increment();
        $column->primary_key();
        $this->columns[] = $column;
    }

    public function string(string $name, int $length = 255): ColumnClause
    {
        $column = new ColumnClause($name, ColumnClause::TYPE_STRING);
        $column->length($length);
        $this->columns[] = $column;

        return $column;
    }

    public function text(string $name): ColumnClause
    {
        $column = new ColumnClause($name, ColumnClause::TYPE_TEXT);
        $this->columns[] = $column;

        return $column;
    }

    public function timestamps() {
        $this->columns[] = (new ColumnClause('created_at', ColumnClause::TYPE_TIMESTAMP))
            ->nullable()
            ->default('NULL');

        $this->columns[] = (new ColumnClause('updated_at', ColumnClause::TYPE_TIMESTAMP))
            ->nullable()
            ->default('NULL');
    }
}
