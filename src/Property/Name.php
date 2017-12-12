<?php

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Formatter\Property\NameFormatter;
use JeroenDesloovere\VCard\Formatter\Property\PropertyFormatterInterface;

class Name implements PropertyInterface
{
    /** @var null|string */
    private $additional;

    /** @var null|string */
    private $firstName;

    /** @var null|string */
    private $lastName;

    /** @var null|string */
    private $prefix;

    /** @var null|string */
    private $suffix;

    /**
     * Name constructor.
     *
     * @param null|string $lastName
     * @param null|string $firstName
     * @param null|string $additional
     * @param null|string $prefix
     * @param null|string $suffix
     */
    public function __construct(
        ?string $lastName = null,
        ?string $firstName = null,
        ?string $additional = null,
        ?string $prefix = null,
        ?string $suffix = null
    ) {
        $this->lastName = $lastName;
        $this->firstName = $firstName;
        $this->additional = $additional;
        $this->prefix = $prefix;
        $this->suffix = $suffix;
    }

    /**
     * @return null|string
     */
    public function getAdditional(): ?string
    {
        return $this->additional;
    }

    /**
     * @return null|string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @return PropertyFormatterInterface
     */
    public function getFormatter(): PropertyFormatterInterface
    {
        return new NameFormatter();
    }

    /**
     * @return null|string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getNode(): string
    {
        return 'N';
    }

    /**
     * @return null|string
     */
    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    /**
     * @return null|string
     */
    public function getSuffix(): ?string
    {
        return $this->suffix;
    }
}
