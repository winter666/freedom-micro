<?php


use Winter666\Freedom\Modules\Config\Config;
use Winter666\Freedom\Modules\Dotenv\Env;

function config(string $name): array {
    return Config::getInstance()->get($name);
}

function env(string $name): string|null {
    return Env::getInstance()->get($name);
}
