<?php


namespace Freedom\Modules\Http\Client;


class Response
{
    protected int $status;
    protected string $json_data;

    public function __construct(\CurlHandle $curl) {
        $this->json_data = curl_exec($curl);
        $this->status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getBody(bool $assoc = false): array|object {
        return json_decode($this->json_data, $assoc);
    }
}
