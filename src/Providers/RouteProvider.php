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
        $controllerResolver = $this->app
            ->singleton('controller_resolver', new ControllerResolver($this->app));

        $this->app->singleton('router', new Router($this->app));
        Router::setControllerResolver($controllerResolver);
        Router::init();
        require_once get_root() . '/router/index.php';
        Router::handleIfActive();
    }
}
