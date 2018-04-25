<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Formatter\Property\NicknameFormatter;
use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Parser\Property\NicknameParser;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Property\Value\StringValue;

final class Nickname extends StringValue implements PropertyInterface, SimpleNodeInterface
{
    public function getFormatter(): NodeFormatterInterface
    {
        return new NicknameFormatter($this);
    }

    public static function getNode(): string
    {
        return 'NICKNAME';
    }

    public static function getParser(): NodeParserInterface
    {
        return new NicknameParser();
    }

    public function isAllowedMultipleTimes(): bool
    {
        return false;
    }
}
