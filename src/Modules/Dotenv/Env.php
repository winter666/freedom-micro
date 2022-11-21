<?php


namespace Freedom\Modules\Dotenv;

class Env
{
    private array $data;

    public function __construct()
    {
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

        $this->data = $data;
    }

    public function get(string $name): ?string {
        $all = $this->data;
        return $all[$name] ?? null;
    }
}
