<?php


namespace Winter666\Freedom\Modules\Helpers\String;


class Str
{
    /**
     * @param int $length
     * @param string $lang
     * @return string
     * @throws Exceptions\LangNotFoundException
     */
    public static function random(int $length = 16, string $lang = Alph::LANG_EN): string {
        Alph::checkLang($lang);

        $word = '';
        $alph = match ($lang) {
            Alph::LANG_RU => Alph::CYRILLIC,
            Alph::LANG_EN => Alph::LATIN,
        };

        for ($i = 0; $i < $length; $i++) {
            $index = rand(0, count($alph) - 1);
            $char = $alph[$index];
            $word .= $char;
        }

        return $word;
    }

    /**
     * @param string $str
     * @param string $lang
     * @return string
     * @throws Exceptions\LangNotFoundException
     */
    public static function slug(string $str, string $lang = Alph::LANG_RU): string {
        Alph::checkLang($lang);

        switch ($lang) {
            case Alph::LANG_RU:
                $str = static::parseRuToEn($str);
                var_dump($str);
                return static::parseSlug($str);
            case Alph::LANG_EN:
            default:
                return static::parseSlug($str);
        }
    }

    /**
     * @param string $enStr
     * @return string
     */
    private static function parseSlug(string $enStr): string {
        $str = mb_strtolower(trim($enStr));
        foreach (Alph::SPECIAL_CHARS as $spec) {
            $str = str_replace($spec, '', $str);
        }

        return preg_replace('/[-]+/', '-', preg_replace('/\s/', '-', $str));
    }

    /**
     * @param string $langStr
     * @return string
     */
    private static function parseRuToEn(string $langStr): string {
        $strArray = mb_str_split($langStr);
        $newStrArray = [];
        foreach ($strArray as $key => $str) {
            $needle = mb_strtolower($str);
            $newStrArray[$key] = Alph::LANG_EN_TO_RU[$needle] ?? $needle;
        }

        return implode('', $newStrArray);
    }
}
