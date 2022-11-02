<?php


namespace Freedom\Modules\Http\Client;


class RequestOptions
{
    private string $base_uri = '';

    public const HTTP_GET = 'GET';
    public const HTTP_POST = 'POST';
    public const HTTP_PUT = 'PUT';
    public const HTTP_DELETE = 'DELETE';
    protected const ALLOW_HTTP_METHODS = [
        self::HTTP_GET,
        self::HTTP_POST,
        self::HTTP_PUT,
        self::HTTP_DELETE,
    ];

    public function inject(\CurlHandle $ch, array $options, bool $withDefaults = true)
    {
        if (isset($options['url'])) {
            $this->setUrlOpt($ch, $options['url']);
        }

        if (isset($options['cookie_session'])) {
            $this->setCookieSessionOpt($ch, $options['cookie_session']);
        }

        if (isset($options['cert_info'])) {
            $this->setCertInfoOpt($ch, $options['cert_info']);
        }

        if (isset($options['connect_only'])) {
            $this->setConnectOnlyOpt($ch, $options['connect_only']);
        }

        if (isset($options['crlf'])) {
            $this->setCrtfOpt($ch, $options['crlf']);
        }

        if (isset($options['fail_on_error'])) {
            $this->setFailOnErrorOpt($ch, $options['fail_on_error']);
        }

        if (isset($options['use_header'])) {
            $this->setHeaderOpt($ch, $options['use_header']);
        }

        if (isset($options['no_body']) || !isset($options['form'])) {
            $this->setNoBodyOpt($ch, isset($options['no_body']) ? $options['no_body'] : true);
        }

        if (isset($options['method'])) {
            $this->setMethodOpt($ch, $options['method']);
        }

        if ($withDefaults) {
            $this->setTimeoutOpt($ch, $options['timeout'] ?? 120);
            $this->setHttpHeaderOpt($ch, $options['head'] ?? []);
            $this->setHttpFormBodyOpt($ch, $options['form'] ?? []);
            $this->setReturnTransferOpt($ch, isset($options['return_transfer']) ? $options['return_transfer'] : true);
            $this->setFailOnErrorOpt($ch, isset($options['fail_on_error']) ? $options['fail_on_error'] : true);
        }
    }

    public function setBaseUri(string $baseUri) {
        $this->base_uri = $baseUri;
    }

    private function setUrlOpt(\CurlHandle $ch, string $url) {
        $url = strlen($this->base_uri) > 0 ? $this->base_uri . $url : $url;
        curl_setopt($ch, CURLOPT_URL, $url);
    }

    private function setCookieSessionOpt(\CurlHandle $ch, bool $value) {
        curl_setopt($ch, CURLOPT_COOKIESESSION, $value);
    }

    private function setCertInfoOpt(\CurlHandle $ch, bool $value) {
        curl_setopt($ch, CURLOPT_CERTINFO, $value);
    }

    private function setConnectOnlyOpt(\CurlHandle $ch, bool $value) {
        curl_setopt($ch, CURLOPT_CONNECT_ONLY, $value);
    }

    private function setCrtfOpt(\CurlHandle $ch, bool $value) {
        curl_setopt($ch, CURLOPT_CRLF, $value);
    }

    private function setFailOnErrorOpt(\CurlHandle $ch, bool $value) {
        curl_setopt($ch, CURLOPT_FAILONERROR, $value);
    }

    private function setHeaderOpt(\CurlHandle $ch, bool $value) {
        curl_setopt($ch, CURLOPT_HEADER, $value);
    }

    private function setHttpHeaderOpt(\CurlHandle $ch, array $value) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $value);
    }

    private function setHttpFormBodyOpt(\CurlHandle $ch, array $value) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $value);
    }

    private function setNoBodyOpt(\CurlHandle $ch, bool $value) {
        curl_setopt($ch, CURLOPT_NOBODY, $value);
    }

    private function setReturnTransferOpt(\CurlHandle $ch, bool $value) {
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, $value);
    }

    private function setTimeoutOpt(\CurlHandle $ch, int $value) {
        curl_setopt($ch, CURLOPT_TIMEOUT, $value);
    }

    private function setMethodOpt(\CurlHandle $ch, string|null $method) {
        if (!in_array(mb_strtoupper($method), static::ALLOW_HTTP_METHODS)) {
            return;
        }

        switch(mb_strtoupper($method)) {
            case static::HTTP_GET:
                curl_setopt($ch, CURLOPT_HTTPGET, true);
                break;
            case static::HTTP_POST:
                curl_setopt($ch, CURLOPT_POST, true);
                break;
            case static::HTTP_PUT:
                curl_setopt($ch, CURLOPT_PUT, true);
                break;
            default:
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, mb_strtoupper($method));
        }
    }
}
