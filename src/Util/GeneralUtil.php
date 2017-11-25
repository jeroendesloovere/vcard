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
     * Returns the browser user agent string.
     *
     * @return string
     */
    public static function getUserAgent(): string
    {
        $browser = 'unknown';

        if (array_key_exists('HTTP_USER_AGENT', $_SERVER)) {
            $browser = strtolower($_SERVER['HTTP_USER_AGENT']);
        }

        return $browser;
    }

    /**
     * Checks if we should return vcard in cal wrapper
     *
     * @return bool
     */
    public static function shouldAttachmentBeCal(): bool
    {
        $browser = self::getUserAgent();

        $matches = [];
        preg_match('/os (\d+)_(\d+)\s+/', $browser, $matches);
        $version = isset($matches[1]) ? ((int) $matches[1]) : 999;

        return ($version < 8);
    }

    /**
     * Is iOS - Check if the user is using an iOS-device
     *
     * @return bool
     */
    public static function isIOS(): bool
    {
        // get user agent
        $browser = self::getUserAgent();

        return (strpos($browser, 'iphone') || strpos($browser, 'ipod') || strpos($browser, 'ipad'));
    }

    /**
     * Is iOS less than 7 (should cal wrapper be returned)
     *
     * @return bool
     */
    public static function isIOS7(): bool
    {
        return (self::isIOS() && self::shouldAttachmentBeCal());
    }
}
