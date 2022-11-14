<?php


namespace Freedom\Modules\Http;


class Response
{
    private int $code;

    public function __construct(int $code = 200)
    {
        $this->code = $code;
    }

    public function send(string $data): string {
        $this->initCode();
        return $data;
    }

    public function sendJson(array $data): string
    {
        header('Content-Type', 'application/json');
        $this->initCode();
        return json_encode($data);
    }

    public function setCode($code): static {
        $this->code = $code;
        return $this;
    }

    private function initCode() {
        http_response_code($this->code);
    }
}
