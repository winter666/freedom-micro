<?php


namespace Winter666\Freedom\Modules\Helpers\String;


use Winter666\Freedom\Modules\Helpers\String\Exceptions\LangNotFoundException;

class Alph
{
    public const LANG_EN = 'en';
    public const LANG_RU = 'ru';
    public const ALLOW_LANG = [
        self::LANG_RU,
        self::LANG_EN,
    ];

    public const LATIN = [
        'A', 'B', 'C', 'D', 'E', 'F', 'G',
        'H', 'I', 'J', 'K', 'L', 'M', 'N',
        'O', 'P', 'Q', 'R', 'S', 'T', 'U',
        'V', 'W', 'X', 'Y', 'Z', 'a', 'b',
        'c', 'd', 'e', 'f', 'g', 'h', 'i',
        'g', 'k', 'l', 'm', 'n', 'o', 'p',
        'q', 'r', 's', 't', 'u', 'v', 'w',
        'x', 'y', 'z',
    ];

    public const CYRILLIC = [
        'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё',
        'Ж', 'З', 'И', 'К', 'Л', 'М', 'Н',
        'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф',
        'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы',
        'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в',
        'г', 'д', 'е', 'ё', 'ж', 'з', 'и',
        'й', 'к', 'л', 'м', 'н', 'о', 'п',
        'р', 'с', 'т', 'у', 'ф', 'х', 'ц',
        'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э',
        'ю', 'я',
    ];

    public const LANG_EN_TO_RU = [
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g',
        'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
        'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k',
        'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
        'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
        'у' => 'y', 'ф' => 'f', 'х' => 'h', 'ц' => 'ts',
        'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shch', 'ъ' => '',
        'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
    ];

    public const SPECIAL_CHARS = [
        '@', '/', '\\', '[', ']', '\'',
        '"', '$', '&', '?', '!', '#', '%',
        '^', '*', '(', ')', '+', '=', '.',
        ',', ':', ';', '`', '~', '№', '<', '>', '-',
    ];

    /**
     * @param string $lang
     * @throws LangNotFoundException
     */
    public static function checkLang(string $lang) {
        if (!in_array($lang, self::ALLOW_LANG)) {
            throw new LangNotFoundException();
        }
    }
}
