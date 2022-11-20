<?php


namespace Freedom\Modules;


class Application
{
    protected array $singletones = [];

    public function singleton($key, $instance)
    {
        $this->singletones[$key] = $instance;
        return $instance;
    }

    public function register(string $provider)
    {
        (new $provider($this))->register();
    }

    public function get($key)
    {
        return $this->singletones[$key];
    }

    public function has($key): bool
    {
        return isset($this->singletones[$key]);
    }
}
