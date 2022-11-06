<?php


namespace Freedom\Modules\Helpers\Arrays;


class Arr
{
    /**
     * Extract value by key from array. Return removed value if found, else return null
     * @param array $array
     * @param string $key
     * @return mixed|null
     */
    public static function shiftValueByKey(array &$array, string $key): string|null {
        if (isset($array[$key])) {
            $value = $array[$key];
            unset($array[$key]);
            return $value;
        }

        return null;
    }

    /**
     * Sorting array
     * @param array $array
     * @param callable $callback
     * @return array
     */
    public static function sort(array $array, callable $callback): array
    {
        usort($array, $callback);
        return array_values($array);
    }

    /**
     * Pluck array
     * @param array $array
     * @param string $column
     * @param int|string|null $key
     * @return array
     */
    public static function pluck(array $array, string $column, int|string $key = null) {
        $haystack = $array;
        if (preg_match('/[.]/', $column)) {
            $keys = explode('.', $column);
            if (count($keys)) {
                $haystack = [];
                foreach ($array as $item) {
                    if (is_array($item)) {
                        $district = null;
                        foreach ($keys as $key) {
                            $district = !is_null($district) ? $district[$key] : $item[$key];
                        }

                        $haystack[] = $district;
                    }
                }
            }

            return $haystack;
        }

        return array_column($array, $column, $key);
    }

    /**
     * Return first item on array
     * @param array $array
     * @return mixed
     */
    public static function first(array $array) {
        return $array[array_key_first($array)] ?? null;
    }

    /**
     * Return last item on array
     * @param array $array
     * @return mixed|null
     */
    public static function last(array $array) {
        return $array[array_key_last($array)] ?? null;
    }

    public static function filter(array $array, callable $callback) {
        return array_values(array_filter($array, $callback));
    }

    public static function length_diff(array $array1, array $array2): int {
        $firstCount = count($array1);
        $secondCount = count($array2);
        if ($firstCount > $secondCount) {
            return $firstCount - $secondCount;
        }

        return $secondCount - $firstCount;
    }
}
