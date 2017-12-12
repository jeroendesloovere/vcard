<?php

namespace JeroenDesloovere\VCard\Formatter\Property;

class PropertyFormatter
{
    /**
     * Escape newline characters according to RFC2425 section 5.8.4.
     *
     * @link http://tools.ietf.org/html/rfc2425#section-5.8.4
     * @param string $value
     * @return string
     */
    protected function escape(string $value): string
    {
        $value = str_replace("\r\n", "\\n", $value);
        $value = str_replace("\n", "\\n", $value);

        return $value;
    }
}