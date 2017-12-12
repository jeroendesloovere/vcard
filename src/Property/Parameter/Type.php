<?php

namespace JeroenDesloovere\VCard\Property\Parameter;

class Type implements PropertyParameterInterface
{
    protected const HOME = 'Home';
    protected const WORK = 'Work';

    public const POSSIBLE_VALUES = [
        self::HOME,
        self::WORK,
    ];

    private $value;

    /**
     * Type constructor.
     *
     * @param string $value
     * @throws \RuntimeException
     */
    public function __construct(string $value)
    {
        if (!in_array($value, self::POSSIBLE_VALUES, true)) {
            throw new \RuntimeException(
                'The given type "'.$value.'" is not allowed. Possible values are: '.implode(', ', self::POSSIBLE_VALUES)
            );
        }

        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getNode(): string
    {
        return 'TYPE';
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return Type
     * @throws \RuntimeException
     */
    public static function home(): self
    {
        return new self(self::HOME);
    }

    /**
     * @return bool
     */
    public function isHome(): bool
    {
        return $this->value === self::HOME;
    }

    /**
     * @return Type
     * @throws \RuntimeException
     */
    public static function work(): self
    {
        return new self(self::WORK);
    }

    /**
     * @return bool
     */
    public function isWork(): bool
    {
        return $this->value === self::WORK;
    }
}
