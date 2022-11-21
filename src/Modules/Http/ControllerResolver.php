<?php


namespace Freedom\Modules\Http;


use Freedom\Modules\Application;
use Freedom\Modules\Resolver;

class ControllerResolver extends Resolver
{
    public function __construct(protected Application $application) {}

    public function getApplication(): Application {
        return $this->application;
    }
}
