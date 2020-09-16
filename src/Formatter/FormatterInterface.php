<?php

declare(strict_types=1);

namespace Dilone\VCard\Formatter;

interface FormatterInterface
{
    public function getContent(array $vCards): string;
    public function getContentType(): string;
    public function getFileExtension(): string;
}
