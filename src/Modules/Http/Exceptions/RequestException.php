<?php


namespace Freedom\Modules\Http\Exceptions;


use Throwable;

class RequestException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = match (true) {
            $code === 400 => 'Bad Request',
            $code === 401 => 'Unauthorized',
            $code === 402 => 'Payment Required',
            $code === 403 => 'Forbidden',
            $code === 404 => 'Resource Not Found',
            $code === 405 => 'Method Not Allowed',
            $code === 419 => 'CSRF Token missmatch',
            $code === 422 => 'Unprocessable Entity',
            $code === 429 => 'Too Many Requests',
            $code === 500 => 'Internal Server Error',
            $code === 501 => 'Not Implemented',
            $code === 502 => 'Bad Gateway',
            $code === 503 => 'Service Unavailable',
            $code === 504 => 'Gateway Timeout',
            default => 'Somethings went wrong!',
        };

        parent::__construct($message, $code, $previous);
    }
}
