<?php

namespace JeroenDesloovere\VCard\Property\Parameter;

class Type
{
    protected const HOME = 'Home';
    protected const WORK = 'Work';

    public const POSSIBLE_VALUES = [
        self::HOME,
        self::WORK,
    ];

    private $type;

    public function __construct(string $type)
    {
        if (!in_array($type, self::POSSIBLE_VALUES)) {
            throw new \Exception(
                'The given type "' . $type . '" is not allowed. Possible values are: ' . implode(', ', self::POSSIBLE_VALUES)
            );
        }

        $this->type = $type;
    }

    public function __toString()
    {
        return $this->type;
    }

    public static function home(): self
    {
        return new self(self::HOME);
    }

    public function isHome(): bool
    {
        return $this->type === self::HOME;
    }

    public static function work(): self
    {
        return new self(self::WORK);
    }

    public function isWork(): bool
    {
        return $this->type === self::WORK;
    }
}