<?php


use Freedom\Modules\Application;
use Freedom\Modules\Config\Config;
use Freedom\Modules\Dotenv\Env;
use Freedom\Modules\Storage\Session;

function app(): Application {
    return Application::getInstance();
}

function config(string $name): array {
    $config = app()->get('config') ?? new Config();
    return $config->get($name);
}

function env(string $name): string|null {
    $env = app()->get('env') ?? new Env();
    return $env->get($name);
}

function config_path() {
    return get_root() . '/config/';
}

function env_path() {
    return get_root() . '/.env';
}

function get_root() {
    return Session::i()->get('project_path') ?? str_replace('/public', '', $_SERVER['DOCUMENT_ROOT']);
}
