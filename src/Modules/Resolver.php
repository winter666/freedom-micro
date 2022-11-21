<?php


namespace Freedom\Modules;



abstract class Resolver
{
    protected array $targets = [];

    public function resolve($key)
    {
        return $this->targets[$key];
    }

    public function push($key, TargetInterface $target)
    {
        $this->targets[$key] = $target;
    }

    public function has($key): bool
    {
        return isset($this->targets[$key]);
    }
}
