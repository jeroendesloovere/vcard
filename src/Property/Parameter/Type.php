<?php

namespace JeroenDesloovere\VCard\Property\Parameter;

use JeroenDesloovere\VCard\Exception\PropertyParameterException;
use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Formatter\Property\Parameter\TypeFormatter;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Parser\Property\Parameter\TypeParser;

final class Type implements PropertyParameterInterface
{
    protected const HOME = 'Home';
    protected const WORK = 'Work';

    public const POSSIBLE_VALUES = [
        self::HOME,
        self::WORK,
    ];

    private $value;

    public function __construct(string $value)
    {
        if (!in_array($value, self::POSSIBLE_VALUES, true)) {
            throw PropertyParameterException::forWrongValue($value, self::POSSIBLE_VALUES);
        }

        $this->value = $value;
    }

    public function __toString()
    {
        return $this->getValue();
    }

    public function getFormatter(): NodeFormatterInterface
    {
        return new TypeFormatter($this);
    }

    public static function getNode(): string
    {
        return 'TYPE';
    }

    public static function getParser(): NodeParserInterface
    {
        return new TypeParser();
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public static function home(): self
    {
        return new self(self::HOME);
    }

    public function isHome(): bool
    {
        return $this->value === self::HOME;
    }

    public static function work(): self
    {
        return new self(self::WORK);
    }

    public function isWork(): bool
    {
        return $this->value === self::WORK;
    }
}
