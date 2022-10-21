<?php


namespace Winter666\Freedom\Modules\DB\Exceptions;


use Throwable;

class DBConnectException extends DBException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
