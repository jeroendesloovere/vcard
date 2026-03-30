<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Formatter\Property\SoundFormatter;
use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Parser\Property\SoundParser;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Property\Value\StringValue;

final class Sound extends StringValue implements PropertyInterface, SimpleNodeInterface
{
    public function getFormatter(): NodeFormatterInterface
    {
        return new SoundFormatter($this);
    }

    public static function getNode(): string
    {
        return 'SOUND';
    }

    public static function getParser(): NodeParserInterface
    {
        return new SoundParser();
    }

    public function isAllowedMultipleTimes(): bool
    {
        return false;
    }
}
