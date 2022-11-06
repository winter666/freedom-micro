<?php


namespace Freedom\Modules\Http;


class Request
{
    private array $fields = [];

    public function __construct(array $values = [])
    {
        $this->fields = $values;
    }

    public function get(string $key)
    {
        return $this->fields[$key] ?? null;
    }
}
