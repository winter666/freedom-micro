<?php


namespace Freedom\Modules\Http\Router;


use Freedom\Modules\Helpers\Arrays\Arr;
use Freedom\Modules\Helpers\String\Str;
use Freedom\Modules\Http\Request;
use Freedom\Modules\Render\Render;

class Router
{
    private static string $uri_regexp = '/^(\{[a-zA-Z_]+\})$/';
    private static string $uri_regexp_has_isset = '/^(\{[a-zA-Z_]+[?]\})$/';
    private static array $path = [];
    private static array $list = [];
    private static string $current_http_method = '';
    public const HTTP_GET = 'GET';
    public const HTTP_POST = 'POST';

    private static function parseUriString(string $strUri): array {
        return Arr::filter(explode('/', $strUri), fn ($i) => (!empty(trim($i))));
    }

    private static function compareUri(array $needle): bool {
        $useUriVal = false;
        $useStrictUriVal = false;
        $uriValPosition = null;
        $dynamicMatch = false;
        foreach ($needle as $key => $nValue) {
            $dynamicMatch = preg_match(static::$uri_regexp_has_isset, $nValue);
            $useStrictUriVal = preg_match(static::$uri_regexp, $nValue);
            if ($dynamicMatch || $useStrictUriVal) {
                $useUriVal = true;
                $uriValPosition = $key;
            }
        }

        $wholeUrlString = implode('/', $needle);
        $count = 0;
        $useStrictUriVal ?
            preg_replace('/\{[a-zA-Z_]+\}/', '', $wholeUrlString, -1, $count) :
            preg_replace('/\{[a-zA-Z_]+[?]\}/', '', $wholeUrlString, -1, $count);

        if ((!$useUriVal && count($needle) !== count(static::$path)) ||
            ($useStrictUriVal && count($needle) !== count(static::$path)) ||
            ($dynamicMatch && Arr::length_diff($needle, static::$path) > $count)) {
            return false;
        }

        foreach (static::$path as $pKey => $path) {
            if (
                !isset($needle[$pKey]) ||
                ($needle[$pKey] !== $path && !$useUriVal) ||
                ($needle[$pKey] !== $path && $useUriVal && $uriValPosition !== $pKey)
            ) {
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

    private static function parseCallback(array|callable $callback, array $values = []) {
        if (is_callable($callback)) {
            return $callback(new Request($values));
        } elseif (is_array($callback) && isset($callback['controller']) && isset($callback['method'])) {
            $controller = new $callback['controller'];
            $method = $callback['method'];
            return $controller->$method(new Request($values));
        }

        return '';
    }

    private static function returnOrRender(callable|array $callback, array $values = []) {
        $value = static::parseCallback($callback, $values);
        if ($value instanceof Render) {
            echo $value->render();
            return;
        }

        echo $value;
    }

    public static function init() {
        if (isset($_REQUEST['p'])) {
            static::$path = static::parseUriString($_GET['p']);
        }

        static::$current_http_method = $_SERVER['REQUEST_METHOD'];
    }

    private static function method(string $uri, array|callable $callback, string $httpMethod) {
        $needle = static::parseUriString($uri);
        $routeID = Str::random();
        static::$list[$routeID] = [
            'uri' => $uri,
            'method' => $httpMethod,
            'active' => !static::checkRoute($needle, $httpMethod),
        ];

        if (!static::$list[$routeID]['active']) {
            return;
        }

        $values = [];
        foreach ($needle as $key => $item) {
            if (preg_match(static::$uri_regexp_has_isset, $item)) {
                $keyName = preg_replace('/[\{\}?]/', '', $item);
                $values[$keyName] = static::$path[$key] ?? null;
            }

            if (preg_match(static::$uri_regexp, $item)) {
                $keyName = preg_replace('/[\{\}]/', '', $item);
                $values[$keyName] = static::$path[$key];
            }
        }

        static::returnOrRender($callback, $values);
    }

    public static function get(string $uri, array|callable $callback) {
        static::method($uri, $callback, static::HTTP_GET);
    }

    public static function post(string $uri, array|callable $callback) {
        static::method($uri, $callback, static::HTTP_POST);
    }

    public static function fallback(array|callable $callback, bool $use_redirect = true, string $fallback_uri = '/404') {
        foreach (static::$list as $val) {
            if ($val['active']) return;
        }

        if (!static::compareUri(static::parseUriString($fallback_uri)) && static::$current_http_method === static::HTTP_GET && $use_redirect) {
            header('Location: ' . $fallback_uri);
        }

        static::returnOrRender($callback);
    }

    public static function getList(): array {
        return static::$list;
    }
}
