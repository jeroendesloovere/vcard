<?php

namespace JeroenDesloovere\VCard\Formatter;

class XmlFormatter extends Formatter implements FormatterInterface
{
    public function getContent(): string
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