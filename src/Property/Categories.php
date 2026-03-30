<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Formatter\Property\CategoriesFormatter;
use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Parser\Property\CategoriesParser;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Property\Value\StringValue;

final class Categories extends StringValue implements PropertyInterface, SimpleNodeInterface
{
    public function getFormatter(): NodeFormatterInterface
    {
        return new CategoriesFormatter($this);
    }

    public static function getNode(): string
    {
        return 'CATEGORIES';
    }

    public static function getParser(): NodeParserInterface
    {
        return new CategoriesParser();
    }

    public function isAllowedMultipleTimes(): bool
    {
        return false;
    }
}
