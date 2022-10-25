<?php


namespace Freedom\Modules\DB\Exceptions;


class DBConnectException extends DBException
{
    public function __construct($message = "", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
