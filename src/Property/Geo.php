<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Formatter\Property\GeoFormatter;
use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Parser\Property\GeoParser;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Property\Value\StringValue;

final class Geo extends StringValue implements PropertyInterface, SimpleNodeInterface
{
    public function getFormatter(): NodeFormatterInterface
    {
        return new GeoFormatter($this);
    }

    public static function getNode(): string
    {
        return 'GEO';
    }

    public static function getParser(): NodeParserInterface
    {
        return new GeoParser();
    }

    public function isAllowedMultipleTimes(): bool
    {
        return false;
    }
}
