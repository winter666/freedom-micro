<?php


namespace Freedom\Modules\DB\Migration;


class ColumnClause
{
    public const TYPE_STRING = 'VARCHAR';
    public const TYPE_INT = 'INT';
    public const TYPE_TEXT = 'TEXT';
    public const TYPE_TIMESTAMP = 'TIMESTAMP';

    private string $name;
    private string $type;
    private array $default = [
        'active' => false,
        'value' => null,
    ];

    private bool $auto_increment = false;
    private bool $primary_key = false;
    private bool $unique_key = false;
    private bool $nullable = false;
    private string $length;

    public function __construct(string $name, string $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    /**
     * @param bool $auto_increment
     */
    public function auto_increment(bool $auto_increment = true): static
    {
        $this->auto_increment = $auto_increment;
        return $this;
    }

    /**
     * @param bool $primary_key
     */
    public function primary_key(bool $primary_key = true): static
    {
        $this->primary_key = $primary_key;
        return $this;
    }

    /**
     * @param bool $unique_key
     */
    public function unique_key(bool $unique_key = true): static
    {
        $this->unique_key = $unique_key;
        return $this;
    }

    /**
     * @param bool $nullable
     */
    public function nullable(bool $nullable = true): static
    {
        $this->nullable = $nullable;
        return $this;
    }

    /**
     * @param string $length
     */
    public function length(string $length): static
    {
        $this->length = $length;
        return $this;
    }

    public function default(string|int $value): static {
        $this->default = [
            'active' => true,
            'value' => $value,
        ];

        return $this;
    }

    public function getSignatureSQL(): string {
        $type = $this->type;
        switch($this->type) {
            case static::TYPE_STRING:
                $type = $type . "({$this->length})";
                break;
            default:
                break;
        }

        $hasDefault = $this->default['active'] && !empty($this->default['value']) ?
            'DEFAULT ' . $this->default['value'] :
            '';

        $hasNull = $this->nullable ? '' : 'NOT NULL';
        $queryStr = "{$this->name} {$type} {$hasNull} {$hasDefault}";

        if ($this->auto_increment) {
            $queryStr .= ' AUTO_INCREMENT';
        }

        // add keys
        if ($this->primary_key) {
            $queryStr .= '#PRIMARY KEY('.$this->name.')';
        }

        if ($this->unique_key) {
            $queryStr .= '#UNIQUE KEY('.$this->name.')';
        }

        return $queryStr;
    }

}
