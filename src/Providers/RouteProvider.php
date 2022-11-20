<?php


namespace Freedom\Providers;


use Freedom\Modules\Http\Controller;
use Freedom\Modules\Http\ControllerResolver;
use Freedom\Modules\Http\Router\Router;

class RouteProvider extends Provider
{
    public function register()
    {
        /**
         * @var ControllerResolver $controllerResolver
         */
        $controllerResolver = $this->application
            ->singleton('controller_resolver', new ControllerResolver());

        $controllerResolver->push('default', new Controller($this->application));
        Router::setControllerResolver($controllerResolver);
    }
}
