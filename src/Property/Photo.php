<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Formatter\Property\PhotoFormatter;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Parser\Property\PhotoParser;
use JeroenDesloovere\VCard\Property\Value\ImageValue;

final class Photo extends ImageValue implements PropertyInterface, NodeInterface
{
    public function getFormatter(): NodeFormatterInterface
    {
        return new PhotoFormatter($this);
    }

    public static function getNode(): string
    {
        return 'PHOTO';
    }

    public static function getParser(): NodeParserInterface
    {
        return new PhotoParser();
    }

    public function isAllowedMultipleTimes(): bool
    {
        return false;
    }
}
