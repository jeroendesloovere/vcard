<?php

namespace JeroenDesloovere\VCard\Util;

/**
 * Class GeneralUtil
 *
 * @package JeroenDesloovere\VCard\Util
 */
class GeneralUtil
{
    /**
     * @param array  $types
     * @param string $default
     *
     * @return string
     */
    public static function parseKey(array $types, string $default = 'default'): string
    {
        return !empty($types) ? implode(';', $types) : $default;
    }

    /**
     * Unescape newline characters according to RFC2425 section 5.8.4.
     * This function will replace escaped line breaks with PHP_EOL.
     *
     * @link http://tools.ietf.org/html/rfc2425#section-5.8.4
     * @param string $text
     * @return string
     */
    public static function unescape($text): string
    {
        return str_replace("\\n", PHP_EOL, $text);
    }
}
