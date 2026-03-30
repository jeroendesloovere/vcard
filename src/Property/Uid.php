<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Formatter\Property\UidFormatter;
use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Parser\Property\UidParser;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Property\Value\StringValue;

final class Uid extends StringValue implements PropertyInterface, SimpleNodeInterface
{
    public function getFormatter(): NodeFormatterInterface
    {
        return new UidFormatter($this);
    }

    public static function getNode(): string
    {
        return 'UID';
    }

    public static function getParser(): NodeParserInterface
    {
        return new UidParser();
    }

    public function isAllowedMultipleTimes(): bool
    {
        return false;
    }
}
