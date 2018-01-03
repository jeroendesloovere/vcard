<?php

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Formatter\Property\FullNameFormatter;
use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Parser\Property\FullNameParser;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Property\Value\StringValue;

final class FullName extends StringValue implements PropertyInterface, SimpleNodeInterface
{
    public function getFormatter(): NodeFormatterInterface
    {
        return new FullNameFormatter($this);
    }

    public static function getNode(): string
    {
        return 'FN';
    }

    public static function getParser(): NodeParserInterface
    {
        return new FullNameParser();
    }

    public function isAllowedMultipleTimes(): bool
    {
        return false;
    }
}
