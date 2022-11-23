<?php


namespace Freedom\Modules\Http;


use Freedom\Modules\Application;
use Freedom\Modules\Helpers\Arrays\Arr;

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

    public function match(string $key, callable $callback): ?string
    {
        if ($this->has($key)) {
            return $key;
        }

        $currentMethod = Arr::last(explode('@', $key));
        $keys = array_keys($this->targets);
        $prepared = [];
        foreach ($keys as $key) {
            $exploded = explode('@', $key);
            $prepared[$key] = [
                'path' => Arr::filter(explode('/', Arr::first($exploded)), fn ($item) => strlen($item) > 0),
                'method' => Arr::last($exploded),
            ];
        }

        foreach ($prepared as $key => $array) {
            $res = $callback($array, $currentMethod, $key);
            if ($res) {
                return $key;
            }
        }

        return null;
    }
}
