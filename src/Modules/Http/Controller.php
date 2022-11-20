<?php


namespace Freedom\Modules\Http;


use Freedom\Modules\Application;
use Freedom\Modules\TargetInterface;

class Controller implements TargetInterface
{
    public function __construct(protected Application $app)
    {

    }
}
