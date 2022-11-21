<?php


namespace Freedom\Modules\Http;


use Freedom\Modules\Application;

class ControllerResolver
{
    public function __construct(protected Application $application) {}

    public function getApplication(): Application {
        return $this->application;
    }

    protected array $targets = [];

    public function resolve($key)
    {
        return $this->targets[$key];
    }

    public function push($key, $target)
    {
        $this->targets[$key] = $target;
    }

    public function has($key): bool
    {
        return isset($this->targets[$key]);
    }
}
