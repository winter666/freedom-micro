<?php


namespace Winter666\Freedom\Modules\Config;


class Config
{
    private static Config|null $instance = null;

    private function __construct() {}

    public static function getInstance(): static {
        if (static::$instance === null) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    public static function issetInstance(): bool {
        return static::$instance !== null;
    }

    public function get(string $configName): array
    {
        $path = config_path() . $configName . '.php';
        if (file_exists($path)) {
            return include($path);
        }

        return [];
    }
}
