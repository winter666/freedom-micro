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
    protected static string $uri_regexp = '/^(\{[a-zA-Z_]+\})$/';
    protected static string $uri_regexp_has_isset = '/^(\{[a-zA-Z_]+[?]\})$/';
    protected static array $path = [];
    protected static string $current_http_method = '';
    protected static bool $isActive = false;
    public const HTTP_GET = 'GET';
    public const HTTP_POST = 'POST';
    protected const STATUS_FALLBACK = 'FALLBACK';

    protected static ControllerResolver $controllerResolver;

    public function __construct(protected Application $app) {}

    public static function setControllerResolver(ControllerResolver $instance)
    {
        static::$controllerResolver = $instance;
    }

    protected static function parseUriString(string $strUri): array {
        return Arr::filter(explode('/', $strUri), fn ($i) => (!empty(trim($i))));
    }

    protected static function compareUri(array $needle): bool {
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

    public static function init() {
        if (isset($_REQUEST['p'])) {
            static::$path = static::parseUriString($_GET['p']);
        }

        static::$current_http_method = $_SERVER['REQUEST_METHOD'] ?? '';
        static::$isActive = strlen(static::$current_http_method) > 0;
    }

    protected static function method(string $uri, array|callable $callback, string $httpMethod) {
        $needle = static::parseUriString($uri);
        $values = [];
        foreach ($needle as $key => $item) {
            if (preg_match(static::$uri_regexp_has_isset, $item)) {
                $keyName = preg_replace('/[\{\}?]/', '', $item);
                $values[$keyName] = static::$path[$key] ?? null;
            }

            if (preg_match(static::$uri_regexp, $item) && isset(static::$path[$key])) {
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
            new ControllerTarget($app, is_array($callback) ? $callback : [$callback], new Request($values))
        );
    }

    public static function handleIfActive() {
        if (static::$isActive) {
            static::handle();
        }
    }

    protected static function handle() {
        $currentKey = '/' . implode('/',static::$path) . '@' . static::$current_http_method;
        $key = static::$controllerResolver->match($currentKey, fn ($array, $cur_method) => static::compareUri($array['path']) && $cur_method == $array['method']);

        if (is_null($key)) {
            if (!static::$controllerResolver->has(static::STATUS_FALLBACK . '@' . static::$current_http_method)) {
                throw new \Exception('Route "/'. implode('/',static::$path) . '" was not found');
            }

            $key = static::STATUS_FALLBACK . '@' . static::$current_http_method;
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
            static::STATUS_FALLBACK . '@' . static::$current_http_method,
            new ControllerTarget($app, is_array($callback) ? $callback : [$callback], new Request())
        );
    }
}
