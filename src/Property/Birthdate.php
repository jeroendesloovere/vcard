<?php

declare(strict_types=1);

namespace Dilone\VCard\Property;

use Dilone\VCard\Formatter\Property\BirthdateFormatter;
use Dilone\VCard\Formatter\Property\NodeFormatterInterface;
use Dilone\VCard\Parser\Property\BirthdateParser;
use Dilone\VCard\Parser\Property\NodeParserInterface;
use Dilone\VCard\Property\Value\DateTimeOrStringValue;

final class Birthdate extends DateTimeOrStringValue implements PropertyInterface, SimpleNodeInterface
{
    public function getFormatter(): NodeFormatterInterface
    {
        return new BirthdateFormatter($this);
    }

    public static function getNode(): string
    {
        return 'BDAY';
    }

    public static function getParser(): NodeParserInterface
    {
        return new BirthdateParser();
    }

    public function isAllowedMultipleTimes(): bool
    {
        return false;
    }
}
