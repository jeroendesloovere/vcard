<?php

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Exception\PropertyException;
use JeroenDesloovere\VCard\Formatter\Property\GenderFormatter;
use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Parser\Property\GenderParser;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Property\Parameter\GenderType;

final class Gender extends SingleStringValue implements PropertyInterface, NodeInterface
{
    protected const FEMALE = 'Female';
    protected const MALE = 'Male';
    protected const NONE = 'None';
    protected const OTHER = 'Other';
    protected const UNKNOWN = 'Unknown';

    public const POSSIBLE_VALUES = [
        self::FEMALE,
        self::MALE,
        self::NONE,
        self::OTHER,
        self::UNKNOWN,
    ];

    public function __construct(string $value = null)
    {
        if ($value === null) {
            throw PropertyException::forEmptyProperty();
        }

        if ($value === '') {
            $value = self::NONE;
        }

        if (!in_array($value, self::POSSIBLE_VALUES, true)) {
            throw PropertyException::forWrongValue($value, self::POSSIBLE_VALUES);
        }

        parent::__construct($value);
    }

    public static function female(): self
    {
        return new self(self::FEMALE);
    }

    public function isFemale(): bool
    {
        return $this->value === self::FEMALE;
    }

    public static function male(): self
    {
        return new self(self::MALE);
    }

    public function isMale(): bool
    {
        return $this->value === self::MALE;
    }

    public static function none(): self
    {
        return new self(self::NONE);
    }

    public function isNone(): bool
    {
        return $this->value === self::NONE;
    }

    public static function other(): self
    {
        return new self(self::OTHER);
    }

    public function isOther(): bool
    {
        return $this->value === self::OTHER;
    }

    public static function unknown(): self
    {
        return new self(self::UNKNOWN);
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
