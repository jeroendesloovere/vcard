<?php

namespace JeroenDesloovere\VCard\Property\Parameter;

use JeroenDesloovere\VCard\Exception\PropertyParameterException;
use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Formatter\Property\Parameter\VersionFormatter;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Parser\Property\Parameter\VersionParser;
use JeroenDesloovere\VCard\Property\SimpleNodeInterface;

final class Version implements PropertyParameterInterface, SimpleNodeInterface
{
    protected const VERSION_3 = '3.0';
    protected const VERSION_4 = '4.0';

    public const POSSIBLE_VALUES = [
        self::VERSION_3,
        self::VERSION_4,
    ];

    private $value;

    public function __construct(string $value)
    {
        if (!in_array($value, self::POSSIBLE_VALUES, true)) {
            throw PropertyParameterException::forWrongValue($value, self::POSSIBLE_VALUES);
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function getFormatter(): NodeFormatterInterface
    {
        return new VersionFormatter($this);
    }

    public static function getNode(): string
    {
        return 'VERSION';
    }

    public static function getParser(): NodeParserInterface
    {
        return new VersionParser();
    }

    public static function version3(): self
    {
        return new self(self::VERSION_3);
    }

    public function isVersion3(): bool
    {
        return $this->value === self::VERSION_3;
    }

    public static function version4(): self
    {
        return new self(self::VERSION_4);
    }

    public function isVersion4(): bool
    {
        return $this->value === self::VERSION_4;
    }
}
