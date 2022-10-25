<?php


namespace Freedom\Modules\Dotenv\Exceptions;


class DotenvException extends \Exception
{
    public const STATUS_CODE_ONE = 1;

    protected const STATUSES = [];

    public function __construct($message = "", $code = self::STATUS_CODE_ONE, \Throwable $previous = null)
    {
        if (!empty(static::STATUSES[$code])) {
            $message = static::STATUSES[$code];
        }

        parent::__construct($message, $code, $previous);
    }
}
