<?php

namespace JeroenDesloovere\VCard\Formatter;

interface FormatterInterface
{
    public function getContent(array $vCards): string;
    public function getContentType(): string;
    public function getFileExtension(): string;
}
