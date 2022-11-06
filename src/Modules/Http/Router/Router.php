<?php


namespace Freedom\Modules\Http\Router;


use Freedom\Modules\Helpers\Arrays\Arr;

class Router
{
    private static array $path = [];
    private static string $current_http_method = '';
    public const HTTP_GET = 'GET';
    public const HTTP_POST = 'POST';

    private static function parseUriString(string $strUri): array {
        return Arr::filter(explode('/', $strUri), fn ($i) => (!empty(trim($i))));
    }

    private static function compareUri(array $needle): bool {
        if (count($needle) !== count(static::$path)) {
            return false;
        }

        foreach (static::$path as $pKey => $path) {
            if (!isset($needle[$pKey]) || $needle[$pKey] !== $path) {
                return false;
            }
        }

        return true;
    }

    private static function compareMethod(string $constantMethod): bool {
        return static::$current_http_method === $constantMethod;
    }

    private static function checkRoute(array $uriArray, string $constantMethod): bool {
        return !static::compareUri($uriArray) || !static::compareMethod($constantMethod);
    }

    public static function init() {
        if (isset($_REQUEST['p'])) {
            static::$path = static::parseUriString($_GET['p']);
        }

        static::$current_http_method = $_SERVER['REQUEST_METHOD'];
    }

    private static function method(string $uri, array|string|callable $callback, string $httpMethod) {
        $needle = static::parseUriString($uri);
        if (static::checkRoute($needle, $httpMethod)) {
            return;
        }

        echo $callback();
    }

    public static function get(string $uri, array|string|callable $callback) {
        static::method($uri, $callback, static::HTTP_GET);
    }

    public static function post(string $uri, array|string|callable $callback) {
        static::method($uri, $callback, static::HTTP_POST);
    }
}
