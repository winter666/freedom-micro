<?php


namespace Freedom\Modules\Config;


class Config
{
    public function get(string $configName): array
    {
        $path = config_path() . $configName . '.php';
        if (file_exists($path)) {
            return include($path);
        }

        return [];
    }
}
