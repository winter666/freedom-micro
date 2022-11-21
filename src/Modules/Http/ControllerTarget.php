<?php


namespace Freedom\Modules\Http;


use Freedom\Modules\Application;
use Freedom\Modules\Helpers\Arrays\Arr;
use Freedom\Modules\Render\Render;
use Freedom\Modules\TargetInterface;

class ControllerTarget implements TargetInterface
{
    public function __construct(protected Application $app, protected array $target, protected array $query_values = []) {}

    public function handle()
    {
        $callback = Arr::first($this->target);
        $request = new Request($this->query_values);
        if (count($this->target) === 1 && is_callable($callback)) {
            return $callback($request);
        } elseif (count($this->target) === 2 && (is_string($callback) && is_string($method = Arr::last($this->target)))) {
            /**
             * @var Controller $controller
             */
            $controller = new $callback($this->app);
            return $controller->$method($request);
        }

        return '';
    }
}
