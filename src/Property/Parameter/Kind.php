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

    /**
     * Kind constructor.
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
        return 'KIND';
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return Kind
     * @throws \RuntimeException
     */
    public static function group(): self
    {
        return new self(self::GROUP);
    }

    /**
     * @return bool
     */
    public function isGroup(): bool
    {
        return $this->value === self::GROUP;
    }

    /**
     * @return Kind
     * @throws \RuntimeException
     */
    public static function individual(): self
    {
        return new self(self::INDIVIDUAL);
    }

    /**
     * @return bool
     */
    public function isIndividual(): bool
    {
        return $this->value === self::INDIVIDUAL;
    }

    /**
     * @return Kind
     * @throws \RuntimeException
     */
    public static function location(): self
    {
        return new self(self::LOCATION);
    }

    /**
     * @return bool
     */
    public function isLocation(): bool
    {
        return $this->value === self::LOCATION;
    }

    /**
     * @return Kind
     * @throws \RuntimeException
     */
    public static function organization(): self
    {
        return new self(self::ORGANIZATION);
    }

    public function isOrganization(): bool
    {
        return $this->value === self::ORGANIZATION;
    }
}