<?php

namespace JeroenDesloovere\VCard\Property\Parameter;

use JeroenDesloovere\VCard\Exception\PropertyParameterException;

final class GenderType
{
    private const EMPTY = '';

    private const MALE = 'M';

    private const FEMALE = 'F';

    private const OTHER = 'O';

    /**
     * N stands for "none or not applicable"
     */
    private const NONE = 'N';

    private const UNKNOWN = 'U';

    public const POSSIBLE_VALUES = [
        self::EMPTY,
        self::MALE,
        self::FEMALE,
        self::OTHER,
        self::NONE,
        self::UNKNOWN,
    ];

    /**
     * @var string
     */
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

    public static function empty(): self
    {
        return new self(self::EMPTY);
    }

    public function isEmpty(): bool
    {
        return $this->value === self::EMPTY;
    }

    public static function male(): self
    {
        return new self(self::MALE);
    }

    public function isMale(): bool
    {
        return $this->value === self::MALE;
    }

    public static function female(): self
    {
        return new self(self::FEMALE);
    }

    public function isFemale(): bool
    {
        return $this->value === self::FEMALE;
    }

    public static function other(): self
    {
        return new self(self::OTHER);
    }

    public function isOther(): bool
    {
        return $this->value === self::OTHER;
    }

    public static function none(): self
    {
        return new self(self::NONE);
    }

    public function isNone(): bool
    {
        return $this->value === self::NONE;
    }

    public static function unknown(): self
    {
        return new self(self::UNKNOWN);
    }

    public function isUnknown(): bool
    {
        return $this->value === self::UNKNOWN;
    }
}
