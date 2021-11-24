<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Formatter\Property\UrlFormatter;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Parser\Property\UrlParser;
use JeroenDesloovere\VCard\Property\Value\StringValue;

final class Url extends StringValue implements PropertyInterface, SimpleNodeInterface
{
    public function getFormatter(): NodeFormatterInterface
    {
        return new UrlFormatter($this);
    }

    public static function getNode(): string
    {
        return 'URL';
    }

    public static function getParser(): NodeParserInterface
    {
        return new UrlParser();
    }

    public function isAllowedMultipleTimes(): bool
    {
        return false;
    }
}
