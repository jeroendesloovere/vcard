<?php

namespace JeroenDesloovere\VCard\Formatter;

class XmlFormatter extends Formatter implements FormatterInterface
{
    public function getContent(array $vCards): string
    {
        return '';
    }

    public function getContentType(): string
    {
        return 'text/xml';
    }

    public function getFileExtension(): string
    {
        return 'xml';
    }
}