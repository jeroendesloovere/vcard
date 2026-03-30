<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Formatter\Property\ProdIdFormatter;
use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Parser\Property\ProdIdParser;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Property\Value\StringValue;

final class ProdId extends StringValue implements PropertyInterface, SimpleNodeInterface
{
    public function getFormatter(): NodeFormatterInterface
    {
        return new ProdIdFormatter($this);
    }

    public static function getNode(): string
    {
        return 'PRODID';
    }

    public static function getParser(): NodeParserInterface
    {
        return new ProdIdParser();
    }

    public function isAllowedMultipleTimes(): bool
    {
        return false;
    }
}
