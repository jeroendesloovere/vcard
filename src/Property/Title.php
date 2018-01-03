<?php

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Formatter\Property\TitleFormatter;
use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Parser\Property\TitleParser;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Property\Value\StringValue;

final class Title extends StringValue implements PropertyInterface, SimpleNodeInterface
{
    public function getFormatter(): NodeFormatterInterface
    {
        return new TitleFormatter($this);
    }

    public static function getNode(): string
    {
        return 'TITLE';
    }

    public static function getParser(): NodeParserInterface
    {
        return new TitleParser();
    }

    public function isAllowedMultipleTimes(): bool
    {
        return false;
    }
}
