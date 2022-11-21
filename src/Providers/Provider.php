<?php


namespace Freedom\Providers;


use Freedom\Modules\Application;

abstract class Provider
{
    public function __construct(protected Application $app)
    {

    }

    abstract public function register();
}
