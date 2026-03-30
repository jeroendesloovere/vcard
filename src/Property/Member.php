<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Formatter\Property\MemberFormatter;
use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Parser\Property\MemberParser;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Property\Value\StringValue;

final class Member extends StringValue implements PropertyInterface, SimpleNodeInterface
{
    public function getFormatter(): NodeFormatterInterface
    {
        return new MemberFormatter($this);
    }

    public static function getNode(): string
    {
        return 'MEMBER';
    }

    public static function getParser(): NodeParserInterface
    {
        return new MemberParser();
    }

    public function isAllowedMultipleTimes(): bool
    {
        return true;
    }
}
