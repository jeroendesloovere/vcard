<?php

declare(strict_types=1);

namespace Dilone\VCard\Property;

use Dilone\VCard\Formatter\Property\NodeFormatterInterface;
use Dilone\VCard\Formatter\Property\PhotoFormatter;
use Dilone\VCard\Parser\Property\NodeParserInterface;
use Dilone\VCard\Parser\Property\PhotoParser;
use Dilone\VCard\Property\Value\ImageValue;

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
