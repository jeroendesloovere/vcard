<?php

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Formatter\Property\NoteFormatter;
use JeroenDesloovere\VCard\Formatter\Property\PropertyFormatterInterface;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Parser\Property\NoteParser;

final class Note implements PropertyInterface
{
    /**
     * @var string
     */
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function fromVcfString(string $value): self
    {
        return new self($value);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getFormatter(): PropertyFormatterInterface
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
