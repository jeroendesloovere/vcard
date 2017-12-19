<?php

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Formatter\Property\FullNameFormatter;
use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Parser\Property\FullNameParser;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;

final class FullName implements PropertyInterface, SimpleNodeInterface
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
