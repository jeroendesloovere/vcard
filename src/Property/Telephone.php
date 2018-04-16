<?php

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Formatter\Property\LogoFormatter;
use JeroenDesloovere\VCard\Formatter\Property\TelephoneFormatter;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Parser\Property\LogoParser;
use JeroenDesloovere\VCard\Parser\Property\TelephoneParser;
use JeroenDesloovere\VCard\Property\Value\ImageValue;

final class Telephone implements PropertyInterface, NodeInterface
{
    /** @var string */
    protected $value;

    public function __construct(string $value)
    {
        $this->value = str_replace(' ', '-', $value);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function getFormatter(): NodeFormatterInterface
    {
        return new TelephoneFormatter($this);
    }

    public static function getNode(): string
    {
        return 'TEL';
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public static function getParser(): NodeParserInterface
    {
        return new TelephoneParser();
    }

    public function isAllowedMultipleTimes(): bool
    {
        return false;
    }
}
