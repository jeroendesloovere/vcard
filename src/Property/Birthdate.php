<?php

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Formatter\Property\BirthdateFormatter;
use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Parser\Property\BirthdateParser;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;

final class Birthdate extends DateTimeValue implements PropertyInterface, SimpleNodeInterface
{
    public function getFormatter(): NodeFormatterInterface
    {
        return new BirthdateFormatter($this);
    }

    public static function getNode(): string
    {
        return 'BDAY';
    }

    public static function getParser(): NodeParserInterface
    {
        return new BirthdateParser();
    }

    public function isAllowedMultipleTimes(): bool
    {
        return false;
    }
}
