<?php

namespace JeroenDesloovere\VCard\Util;

/**
 * Class UserAgentUtil
 *
 * @package JeroenDesloovere\VCard\Util
 */
class UserAgentUtil
{
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
        preg_match('/os (\d+)_(\d+)/', $browser, $matches);
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
