<?php

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Exception\PropertyException;
use JeroenDesloovere\VCard\Formatter\Property\GenderFormatter;
use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Parser\Property\GenderParser;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;

final class Gender implements PropertyInterface, NodeInterface
{
    protected const EMPTY = '';
    protected const FEMALE = 'F';
    protected const MALE = 'M';
    protected const NONE = 'N';
    protected const OTHER = 'O';
    protected const UNKNOWN = 'U';

    public const POSSIBLE_VALUES = [
        self::EMPTY,
        self::FEMALE,
        self::MALE,
        self::NONE,
        self::OTHER,
        self::UNKNOWN,
    ];

    /**
     * @var string
     */
    private $value;

    /**
     * @var null|string
     */
    private $note;

    public function __construct(?string $value = '', ?string $note = null)
    {
        if (!in_array($value, self::POSSIBLE_VALUES, true)) {
            throw PropertyException::forWrongValue($value, self::POSSIBLE_VALUES);
        }

        if ($value === self::EMPTY && $note === null) {
            throw PropertyException::forEmptyProperty();
        }

        $this->value = $value;
        $this->note = $note;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getNote(): string
    {
        return $this->note;
    }

    public static function empty(string $note = null): self
    {
        return new self(self::EMPTY, $note);
    }

    public function isEmpty(): bool
    {
        return $this->value === self::EMPTY;
    }

    public static function female(string $note = null): self
    {
        return new self(self::FEMALE, $note);
    }

    public function isFemale(): bool
    {
        return $this->value === self::FEMALE;
    }

    public static function male(string $note = null): self
    {
        return new self(self::MALE, $note);
    }

    public function isMale(): bool
    {
        return $this->value === self::MALE;
    }

    public static function none(string $note = null): self
    {
        return new self(self::NONE, $note);
    }

    public function isNone(): bool
    {
        return $this->value === self::NONE;
    }

    public static function other(string $note = null): self
    {
        return new self(self::OTHER, $note);
    }

    public function isOther(): bool
    {
        return $this->value === self::OTHER;
    }

    public static function unknown(string $note = null): self
    {
        return new self(self::UNKNOWN, $note);
    }

    public function isUnknown(): bool
    {
        return $this->value === self::UNKNOWN;
    }

    public function getFormatter(): NodeFormatterInterface
    {
        return new GenderFormatter($this);
    }

    public static function getNode(): string
    {
        return 'GENDER';
    }

    public static function getParser(): NodeParserInterface
    {
        return new GenderParser();
    }

    public function isAllowedMultipleTimes(): bool
    {
        return false;
    }
}
