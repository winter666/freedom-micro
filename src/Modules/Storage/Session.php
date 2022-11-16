<?php


namespace Freedom\Modules\Storage;


class Session
{

    protected static Session $instance;

    private function __construct() {}

    public static function i(): Session
    {
        if (empty($_SESSION['instance'])) {
            session_start();
            $_SESSION['instance'] = time();
            self::$instance = new static();
        }

        return self::$instance;
    }

    public function get(string $key)
    {
        return $_SESSION[$key] ?? null;
    }

    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public function set(string $key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function push(string $key, $value)
    {
        if (!is_array($_SESSION[$key] ?? [])) {
            throw new \Exception('Value must be an array');
        }

        $_SESSION[$key][] = $value;
    }

    public function delete(string $key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public static function destroy()
    {
        if (!empty($_SESSION)) {
            session_destroy();
        }
    }
}
