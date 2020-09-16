<?php

declare(strict_types=1);

namespace Dilone\VCard\Property;

use Dilone\VCard\Formatter\Property\NoteFormatter;
use Dilone\VCard\Formatter\Property\NodeFormatterInterface;
use Dilone\VCard\Parser\Property\NodeParserInterface;
use Dilone\VCard\Parser\Property\NoteParser;
use Dilone\VCard\Property\Value\StringValue;

final class Note extends StringValue implements PropertyInterface, SimpleNodeInterface
{
    public function getFormatter(): NodeFormatterInterface
    {
        return new NoteFormatter($this);
    }

    public static function getNode(): string
    {
        return 'NOTE';
    }

    public static function getParser(): NodeParserInterface
    {
        return new NoteParser();
    }

    public function isAllowedMultipleTimes(): bool
    {
        return false;
    }
}
