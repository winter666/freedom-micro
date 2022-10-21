<?php


namespace Serge\Config\Config;


class Config
{
    protected string $config_path;

    public function __construct()
    {
        $this->config_path = str_replace('/public', '', $_SERVER['DOCUMENT_ROOT']). '/config/';
    }

    public static function getInstance(): static {
        return new static;
    }

    public function get(string $configName): array
    {
        $path = $this->config_path . $configName . '.php';
        if (file_exists($path)) {
            return include($path);
        }

        return [];
    }
}
