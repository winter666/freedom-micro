<?php


namespace Freedom\Modules\Helpers\String\Exceptions;


class LangNotFoundException extends \Exception
{
    public function __construct($message = "Language not found", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
