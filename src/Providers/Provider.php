<?php


namespace Freedom\Providers;


use Freedom\Modules\Application;

abstract class Provider implements ProviderInterface
{
    public function __construct(protected Application $application)
    {

    }
}
