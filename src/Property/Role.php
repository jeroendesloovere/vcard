<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Formatter\Property\RoleFormatter;
use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Parser\Property\RoleParser;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Property\Value\StringValue;

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
