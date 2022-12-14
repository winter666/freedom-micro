<?php


namespace Freedom\Modules\Http;


use Freedom\Modules\Application;
use Freedom\Modules\Helpers\Arrays\Arr;
use Freedom\Modules\ReflectionService;

class ControllerTarget
{
    public function __construct(protected Application $app, protected array $target, protected Request $request) {}

    public function handle()
    {
        $callback = Arr::first($this->target);
        if (count($this->target) === 1 && is_callable($callback)) {
            return $callback($this->request);
        } elseif (count($this->target) === 2 && (is_string($callback) && is_string($method = Arr::last($this->target)))) {
            /**
             * @var Controller $controller
             */
            $reflectionService = new ReflectionService($callback, $method, $this->request);
            $controller = new $callback($this->app);
            return $controller->$method(...$reflectionService->getDependencies());
        }

        return '';
    }
}
