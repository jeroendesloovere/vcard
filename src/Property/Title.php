<?php

declare(strict_types=1);

namespace Dilone\VCard\Property;

use Dilone\VCard\Formatter\Property\TitleFormatter;
use Dilone\VCard\Formatter\Property\NodeFormatterInterface;
use Dilone\VCard\Parser\Property\TitleParser;
use Dilone\VCard\Parser\Property\NodeParserInterface;
use Dilone\VCard\Property\Value\StringValue;

final class Title extends StringValue implements PropertyInterface, SimpleNodeInterface
{
    public function getFormatter(): NodeFormatterInterface
    {
        return new TitleFormatter($this);
    }

    public static function getNode(): string
    {
        return 'TITLE';
    }

    public static function getParser(): NodeParserInterface
    {
        return new TitleParser();
    }

    public function isAllowedMultipleTimes(): bool
    {
        return false;
    }
}
