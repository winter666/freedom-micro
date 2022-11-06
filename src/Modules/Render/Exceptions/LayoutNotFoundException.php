<?php


namespace Freedom\Modules\Render\Exceptions;


use Throwable;

class LayoutNotFoundException extends \Exception
{
    public function __construct($name = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct("Layout {$name} was not found!", $code, $previous);
    }
}
