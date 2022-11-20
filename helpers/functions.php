<?php


use Freedom\Modules\Config\Config;
use Freedom\Modules\Dotenv\Env;
use Freedom\Modules\Storage\Session;

function config(string $name): array {
    return (new Config())->get($name);
}

function env(string $name): string|null {
    return (new Env())->get($name);
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
