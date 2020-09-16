<?php

declare(strict_types=1);

namespace Dilone\VCard\Property;

use Dilone\VCard\Formatter\Property\AnniversaryFormatter;
use Dilone\VCard\Formatter\Property\NodeFormatterInterface;
use Dilone\VCard\Parser\Property\AnniversaryParser;
use Dilone\VCard\Parser\Property\NodeParserInterface;
use Dilone\VCard\Property\Value\DateTimeOrStringValue;

final class Anniversary extends DateTimeOrStringValue implements PropertyInterface, SimpleNodeInterface
{
    public function getFormatter(): NodeFormatterInterface
    {
        return new AnniversaryFormatter($this);
    }

    public static function getNode(): string
    {
        return 'ANNIVERSARY';
    }

    public static function getParser(): NodeParserInterface
    {
        return new AnniversaryParser();
    }

    public function isAllowedMultipleTimes(): bool
    {
        return false;
    }
}
