<?php

namespace JeroenDesloovere\VCard\Property\Parameter;

class Kind implements PropertyParameterInterface
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

    private $value;

    public function __construct(string $value)
    {
        if (!in_array($value, self::POSSIBLE_VALUES)) {
            throw new \Exception(
                'The given type "' . $value . '" is not allowed. Possible values are: ' . implode(', ', self::POSSIBLE_VALUES)
            );
        }

        $this->value = $value;
    }

    public function __toString()
    {
        return $this->value;
    }

    public function getNode(): string
    {
        return 'KIND';
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public static function group(): self
    {
        return new self(self::GROUP);
    }

    public function isGroup(): bool
    {
        return $this->value === self::GROUP;
    }

    public static function individual(): self
    {
        return new self(self::INDIVIDUAL);
    }

    public function isIndividual(): bool
    {
        return $this->value === self::INDIVIDUAL;
    }

    public static function location(): self
    {
        return new self(self::LOCATION);
    }

    public function isLocation(): bool
    {
        return $this->value === self::LOCATION;
    }

    public static function organization(): self
    {
        return new self(self::ORGANIZATION);
    }

    public function isOrganization(): bool
    {
        return $this->value === self::ORGANIZATION;
    }
}
