<?php

namespace JeroenDesloovere\VCard\Property;

class Kind
{
    protected const GROUP = 'Group';
    protected const INDIVIDUAL = 'Individual';
    protected const LOCATION = 'Location';
    protected const ORGANIZATION = 'Organization';

    public const POSSIBLE_VALUES = [
        self::GROUP,
        self::INDIVIDUAL,
        self::LOCATION,
        self::ORGANIZATION,
    ];

    private $kind;

    public function __construct(string $kind)
    {
        if (in_array($kind, self::POSSIBLE_VALUES)) {
            throw new \Exception(
                'The given type "' . $kind . '" is not allowed. Possible values are: ' . implode(', ', self::POSSIBLE_VALUES)
            );
        }

        $this->kind = $kind;
    }

    public function __toString()
    {
        return $this->kind;
    }

    public static function group(): self
    {
        return new self(self::GROUP);
    }

    public function isGroup(): bool
    {
        return $this->kind === self::GROUP;
    }

    public static function individual(): self
    {
        return new self(self::INDIVIDUAL);
    }

    public function isIndividual(): bool
    {
        return $this->kind === self::INDIVIDUAL;
    }

    public static function location(): self
    {
        return new self(self::LOCATION);
    }

    public function isLocation(): bool
    {
        return $this->kind === self::LOCATION;
    }

    public static function organization(): self
    {
        return new self(self::ORGANIZATION);
    }

    public function isOrganization(): bool
    {
        return $this->kind === self::ORGANIZATION;
    }
}