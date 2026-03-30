<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Formatter\Property\TzFormatter;
use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Parser\Property\TzParser;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Property\Value\StringValue;

final class Tz extends StringValue implements PropertyInterface, SimpleNodeInterface
{
    public function getFormatter(): NodeFormatterInterface
    {
        return new TzFormatter($this);
    }

    public static function getNode(): string
    {
        return 'TZ';
    }

    public static function getParser(): NodeParserInterface
    {
        return new TzParser();
    }

    public function isAllowedMultipleTimes(): bool
    {
        return false;
    }
}
