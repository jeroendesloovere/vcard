<?php

declare(strict_types=1);

namespace Dilone\VCard\Property;

use Dilone\VCard\Formatter\Property\FullNameFormatter;
use Dilone\VCard\Formatter\Property\NodeFormatterInterface;
use Dilone\VCard\Parser\Property\FullNameParser;
use Dilone\VCard\Parser\Property\NodeParserInterface;
use Dilone\VCard\Property\Value\StringValue;

final class FullName extends StringValue implements PropertyInterface, SimpleNodeInterface
{
    public function getFormatter(): NodeFormatterInterface
    {
        return new FullNameFormatter($this);
    }

    public static function getNode(): string
    {
        return 'FN';
    }

    public static function getParser(): NodeParserInterface
    {
        return new FullNameParser();
    }

    public function isAllowedMultipleTimes(): bool
    {
        return false;
    }
}
