<?php

declare(strict_types=1);

namespace Dilone\VCard\Property;

use Dilone\VCard\Formatter\Property\RoleFormatter;
use Dilone\VCard\Formatter\Property\NodeFormatterInterface;
use Dilone\VCard\Parser\Property\RoleParser;
use Dilone\VCard\Parser\Property\NodeParserInterface;
use Dilone\VCard\Property\Value\StringValue;

final class Role extends StringValue implements PropertyInterface, SimpleNodeInterface
{
    public function getFormatter(): NodeFormatterInterface
    {
        return new RoleFormatter($this);
    }

    public static function getNode(): string
    {
        return 'ROLE';
    }

    public static function getParser(): NodeParserInterface
    {
        return new RoleParser();
    }

    public function isAllowedMultipleTimes(): bool
    {
        return false;
    }
}
