<?php

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Formatter\Property\NoteFormatter;
use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Parser\Property\NoteParser;

final class Note implements PropertyInterface, SimpleNodeInterface
{
    /**
     * @var string
     */
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

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
