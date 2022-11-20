<?php


namespace Freedom\Providers;

use Freedom\Modules\Application;
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

        $this->application->singleton('router', fn(Application $app) => new Router($app));
        Router::setControllerResolver($controllerResolver);
    }
}
