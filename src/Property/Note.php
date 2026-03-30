<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Formatter\Property\NoteFormatter;
use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Parser\Property\NoteParser;
use JeroenDesloovere\VCard\Property\Value\StringValue;

final class Note extends StringValue implements PropertyInterface, SimpleNodeInterface
{
    public function getFormatter(): NodeFormatterInterface
    {
        return new NoteFormatter($this);
    }

    public static function getNode(): string
    {
        return 'NOTE';
    }

    public static function getParser(): NodeParserInterface
    {
        return new NoteParser();
    }

    public function isAllowedMultipleTimes(): bool
    {
        return false;
    }
}
