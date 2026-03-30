<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Formatter\Property\KeyFormatter;
use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Parser\Property\KeyParser;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Property\Value\StringValue;

final class Key extends StringValue implements PropertyInterface, SimpleNodeInterface
{
    public function getFormatter(): NodeFormatterInterface
    {
        return new KeyFormatter($this);
    }

    public static function getNode(): string
    {
        return 'KEY';
    }

    public static function getParser(): NodeParserInterface
    {
        return new KeyParser();
    }

    public function isAllowedMultipleTimes(): bool
    {
        return true;
    }
}
