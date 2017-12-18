<?php

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Formatter\Property\FullNameFormatter;
use JeroenDesloovere\VCard\Formatter\Property\PropertyFormatterInterface;
use JeroenDesloovere\VCard\Parser\Property\FullNameParser;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;

final class FullName implements PropertyInterface
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
        return new FullNameFormatter($this);
    }

    public static function getNode(): string
    {
        return 'FN';
    }

    public static function getParser(): NodeParserInterface
    {
        return new FullNameParser();
    }

    public function isAllowedMultipleTimes(): bool
    {
        return false;
    }
}
