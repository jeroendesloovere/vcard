<?php

declare(strict_types=1);

namespace Dilone\VCard\Property;

use Dilone\VCard\Formatter\Property\NodeFormatterInterface;
use Dilone\VCard\Formatter\Property\LogoFormatter;
use Dilone\VCard\Parser\Property\NodeParserInterface;
use Dilone\VCard\Parser\Property\LogoParser;
use Dilone\VCard\Property\Value\ImageValue;

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
