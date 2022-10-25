<?php


namespace Freedom\Modules\Dotenv;

class Env
{
    private static Env|null $instance = null;

    private function __construct() {}

    public static function getInstance(): static {
        if (static::$instance === null) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    public function getAll(): array {
        $fileContent = file_get_contents(env_path());
        $rows = explode("\n", $fileContent);
        $data = [];
        foreach ($rows as $row) {
            $item = explode('=', $row);
            $name = $item[0];
            if (str_starts_with($name, '#')) {
                continue;
            }

            $value = $item[1] ?? null;
            if (!empty($name) && empty($data[$name])) {
                $data[trim($name)] = trim($value);
            }
        }

        return $data;
    }

    public function get(string $name): string|null {
        $all = $this->getAll();
        return $all[$name] ?? null;
    }
}
