<?php

namespace JeroenDesloovere\VCard\Formatter;

use JeroenDesloovere\VCard\Property\Parameter\PropertyParameterInterface;
use JeroenDesloovere\VCard\Property\PropertyInterface;
use JeroenDesloovere\VCard\VCard;

final class VcfFormatter implements FormatterInterface
{
    public function getContent(array $vCards): string
    {
        $string = '';

        /**
         * @var VCard $vCard
         */
        foreach ($vCards as $vCard) {
            $string .= "BEGIN:VCARD\r\n";

            /**
             * @var PropertyParameterInterface $parameter
             */
            foreach ($vCard->getParameters() as $parameter) {
                $string .= $this->fold($parameter->getFormatter()->getVcfString() . "\r\n");
            }

            /**
             * @var PropertyInterface $property
             */
            foreach ($vCard->getProperties() as $property) {
                $string .= $this->fold($property->getFormatter()->getVcfString() . "\r\n");
            }

            $string .= "END:VCARD\r\n";
        }

        return $string;
    }

    public function getContentType(): string
    {
        return 'text/x-vcard';
    }

    public function getFileExtension(): string
    {
        return 'vcf';
    }

    /**
     * Fold a line according to RFC2425 section 5.8.1.
     *
     * @link   http://tools.ietf.org/html/rfc2425#section-5.8.1
     * @param  string $value
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
