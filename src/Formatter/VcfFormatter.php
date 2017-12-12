<?php

namespace JeroenDesloovere\VCard\Formatter;

use JeroenDesloovere\VCard\Property\PropertyInterface;
use JeroenDesloovere\VCard\VCard;

/**
 * Class VcfFormatter
 *
 * @package JeroenDesloovere\VCard\Formatter
 */
class VcfFormatter implements FormatterInterface
{
    /**
     * @param array $vCards
     *
     * @return string
     */
    public function getContent(array $vCards): string
    {
        $string = "BEGIN:VCARD\r\n";
        $string .= "VERSION:4.0\r\n";
        $string .= 'REV:'.date('Y-m-d').'T'.date('H:i:s')."Z\r\n";

        /** @var VCard $vCard */
        foreach ($vCards as $vCard) {
            /** @var PropertyInterface $property */
            foreach ($vCard->getProperties() as $property) {
                $string .= $this->fold($property->getFormatter()->convertToVcfString($property)."\r\n");
            }
        }

        $string .= "END:VCARD\r\n";

        return $string;
    }

    /**
     * @return string
     */
    public function getContentType(): string
    {
        return 'text/x-vcard';
    }

    /**
     * @return string
     */
    public function getFileExtension(): string
    {
        return 'vcf';
    }

    /**
     * Fold a line according to RFC2425 section 5.8.1.
     *
     * @link http://tools.ietf.org/html/rfc2425#section-5.8.1
     * @param string $value
     * @return string
     */
    private function fold(string $value): string
    {
        if (strlen($value) <= 75) {
            return $value;
        }

        // split, wrap and trim trailing separator
        return substr(chunk_split($value, 73, "\r\n "), 0, -3);
    }
}
