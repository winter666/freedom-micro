<?php


namespace Freedom\Modules\Http\Router;

use Freedom\Modules\Application;
use Freedom\Modules\Helpers\Arrays\Arr;
use Freedom\Modules\Http\ControllerResolver;
use Freedom\Modules\Http\ControllerTarget;
use Freedom\Modules\Http\Request;
use Freedom\Modules\Render\Render;

class Router
{
    private static string $uri_regexp = '/^(\{[a-zA-Z_]+\})$/';
    private static string $uri_regexp_has_isset = '/^(\{[a-zA-Z_]+[?]\})$/';
    private static array $path = [];
    private static string $current_http_method = '';
    public const HTTP_GET = 'GET';
    public const HTTP_POST = 'POST';
    protected const STATUS_FALLBACK = 'FALLBACK';

    protected static ControllerResolver $controllerResolver;

    public function __construct(protected Application $app) {}

    public static function setControllerResolver(ControllerResolver $instance)
    {
        static::$controllerResolver = $instance;
    }

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

        static::$current_http_method = $_SERVER['REQUEST_METHOD'] ?? '';
    }

    private static function method(string $uri, array|callable $callback, string $httpMethod) {
        $needle = static::parseUriString($uri);
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

        if (static::$current_http_method === self::HTTP_POST) {
            foreach ($_POST as $key => $val) {
                $values[$key] = $val;
            }
        }

        $app = static::$controllerResolver->getApplication();
        static::$controllerResolver->push(
            $uri . '@' . $httpMethod,
            new ControllerTarget($app, is_array($callback) ? $callback : [$callback], $values)
        );
    }

    public static function handle() {
        $key = '/' . implode('/',static::$path) . '@' . static::$current_http_method;

        if (!static::$controllerResolver->has($key)) {
            if (!static::$controllerResolver->has(static::STATUS_FALLBACK)) {
                throw new \Exception('Route "/'. implode('/',static::$path) . '" was not found');
            }

            $key = static::STATUS_FALLBACK;
        }

        /**
         * @var ControllerTarget $target
         */
        $target = static::$controllerResolver->resolve($key);
        $value = $target->handle();
        if ($value instanceof Render) {
            echo $value->render();
            return;
        }

        echo $value;
    }

    public static function get(string $uri, array|callable $callback) {
        static::method($uri, $callback, static::HTTP_GET);
    }

    public static function post(string $uri, array|callable $callback) {
        static::method($uri, $callback, static::HTTP_POST);
    }

    public static function fallback(array|callable $callback) {
        $app = static::$controllerResolver->getApplication();
        static::$controllerResolver->push(
            static::STATUS_FALLBACK,
            new ControllerTarget($app, is_array($callback) ? $callback : [$callback])
        );
    }
}
