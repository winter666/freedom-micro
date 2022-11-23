<?php


namespace Freedom\Modules;


class Application
{
    protected array $singletones = [];
    protected static Application $instance;

    protected function __construct() {}

    public static function getInstance(): Application
    {
        if (empty(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

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
