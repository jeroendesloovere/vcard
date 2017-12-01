<?php

namespace JeroenDesloovere\VCard\Util;

use JeroenDesloovere\VCard\Exception\InvalidUrlException;

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
     * Escape newline characters according to RFC2425 section 5.8.4.
     *
     * @link http://tools.ietf.org/html/rfc2425#section-5.8.4
     *
     * @param string $text
     *
     * @return string
     */
    public static function escape(string $text): string
    {
        $text = str_replace(array("\r\n", "\n"), "\\n", $text);

        return $text;
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

    /**
     * Fold a line according to RFC2425 section 5.8.1.
     *
     * @link http://tools.ietf.org/html/rfc2425#section-5.8.1
     *
     * @param string $text
     *
     * @return bool|string
     */
    public static function fold($text)
    {
        if (\strlen($text) <= 75) {
            return $text;
        }

        // split, wrap and trim trailing separator
        return substr(chunk_split($text, 73, "\r\n "), 0, -3);
    }

    /**
     * @param string $url
     *
     * @return null|string
     * @throws InvalidUrlException
     */
    public static function getMimeType(string $url): ?string
    {
        $mimeType = null;

        $url = trim($url);

        if ($url === '') {
            throw new InvalidUrlException();
        }

        //Is this URL for a remote resource?
        if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
            $headers = get_headers($url, 1);

            if (array_key_exists('Content-Type', $headers)) {
                $mimeType = $headers['Content-Type'];
            }
        } else {
            //Local file, so inspect it directly
            $mimeType = mime_content_type($url);
        }

        if (strpos($mimeType, ';') !== false) {
            $mimeType = strstr($mimeType, ';', true);
        }

        return $mimeType;
    }
}
