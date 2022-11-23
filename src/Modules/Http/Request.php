<?php


namespace Freedom\Modules\Http;


class Request
{
    public function __construct(protected array $fields = []) {}

    public function get(string $key)
    {
        return $this->fields[$key] ?? null;
    }
}
