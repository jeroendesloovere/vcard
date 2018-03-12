<?php

namespace JeroenDesloovere\VCard\Formatter;

use JeroenDesloovere\VCard\Property\NodeInterface;
use JeroenDesloovere\VCard\VCard;

final class VcfFormatter implements FormatterInterface
{
    public function getContent(array $vCards): string
    {
        $string = '';

        /** @var VCard $vCard */
        foreach ($vCards as $vCard) {
            $string .= "BEGIN:VCARD\r\n";
            $this->setNodesToString($vCard->getParameters(), $string);
            $this->setNodesToString($vCard->getProperties(), $string);
            $string .= "END:VCARD\r\n";
        }

        return $string;
    }

    public function getContentType(): string
    {
        return 'text/vcard';
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

    /**
     * @param NodeInterface[] $nodes
     * @param string $string
     */
    private function setNodesToString(array $nodes, string &$string): void
    {
        /** @var NodeInterface $parameter */
        foreach ($nodes as $parameter) {
            $string .= $this->fold($parameter->getFormatter()->getVcfString() . "\r\n");
        }
    }
}
