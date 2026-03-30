<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Formatter\Property\LogoFormatter;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Parser\Property\LogoParser;
use JeroenDesloovere\VCard\Property\Value\ImageValue;

final class Logo extends ImageValue implements PropertyInterface, NodeInterface
{
    public function getFormatter(): NodeFormatterInterface
    {
        return new LogoFormatter($this);
    }

    public static function getNode(): string
    {
        return 'LOGO';
    }

    public static function getParser(): NodeParserInterface
    {
        return new LogoParser();
    }

    public function isAllowedMultipleTimes(): bool
    {
        return false;
    }
}
