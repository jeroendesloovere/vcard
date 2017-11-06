<?php

namespace JeroenDesloovere\VCard\Formatter;

class VcfFormatter implements FormatterInterface
{
    public function getContent(): string
    {
        return '';
    }

    public function getContentType(): string
    {
        return 'text/x-vcard';
    }

    public function getFileExtension(): string
    {
        return 'vcf';
    }
}