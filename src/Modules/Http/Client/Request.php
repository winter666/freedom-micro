<?php


namespace Freedom\Modules\Http;


use Freedom\Modules\Http\Exceptions\RequestException;

class Request
{
    private \CurlHandle $curl;
    public RequestOptions $options;
    public Response $response;

    public function __construct()
    {
        $this->curl = curl_init();
        $this->options = new RequestOptions();
    }

    /**
     * Set options for request as a key-value array. Allowed key-values:
     * url, cookie_session, cert_info, connect_only, crlf, fail_on_error,
     * use_header, no_body, return_transfer, base_uri, method, head, form
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options->inject($this->curl, $options);
    }

    public function setBaseUri(string $baseUri)
    {
        $this->options->setBaseUri($baseUri);
    }

    public function fetchRequest(string|null $url = null, string|null $method = null) {
        $options = [];
        if (!is_null($url)) {
            $options['url'] = $url;
        }

        if (!is_null($method)) {
            $options['method'] = $method;
        }

        $this->options->inject($this->curl, $options, false);
        $this->response = new Response($this->curl);

        $status = $this->response->getStatus();
        if ($status === 0 || $status >= 400) {
            throw new RequestException('', $status);
        }
    }

    public function getResponse(): Response {
        return $this->response;
    }

    public function get(string $url) {
        $this->options->inject($this->curl, [
            'return_transfer' => true,
        ]);

        $this->fetchRequest($url, RequestOptions::HTTP_GET);
    }

    public function post(string $url, array $body, array $head = []) {
        $this->options->inject($this->curl, [
            'return_transfer' => true,
            'form' => $body,
            'head' => $head,
        ]);

        $this->fetchRequest($url, RequestOptions::HTTP_POST);
    }

    public function put(string $url, array $body, array $head = []) {
        $this->options->inject($this->curl, [
            'return_transfer' => true,
            'form' => $body,
            'head' => $head,
        ]);

        $this->fetchRequest($url, RequestOptions::HTTP_PUT);
    }

    public function delete(string $url, array $body, array $head = []) {
        $this->options->inject($this->curl, [
            'return_transfer' => true,
            'form' => $body,
            'head' => $head,
        ]);

        $this->fetchRequest($url, RequestOptions::HTTP_DELETE);
    }
}