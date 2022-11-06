<?php


namespace Freedom\Modules\Render\Exceptions;


use Throwable;

class TemplateNotFoundException extends \Exception
{
    public function __construct($name = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct("Template {$name} was not found!", $code, $previous);
    }
}
