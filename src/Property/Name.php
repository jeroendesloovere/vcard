<?php

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Exception\PropertyException;
use JeroenDesloovere\VCard\Formatter\Property\NameFormatter;
use JeroenDesloovere\VCard\Formatter\Property\PropertyFormatterInterface;
use JeroenDesloovere\VCard\Parser\Property\NameParser;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;

final class Name implements PropertyInterface
{
    /**
     * @var null|string
     */
    private $additional;

    /**
     * @var null|string
     */
    private $firstName;

    /**
     * @var null|string
     */
    private $lastName;

    /**
     * @var null|string
     */
    private $prefix;

    /**
     * @var null|string
     */
    private $suffix;

    public function __construct(
        ?string $lastName = null,
        ?string $firstName = null,
        ?string $additional = null,
        ?string $prefix = null,
        ?string $suffix = null
    ) {
        if ($lastName === null && $firstName === null && $additional === null && $prefix === null && $suffix === null) {
            throw PropertyException::forEmptyProperty();
        }

        $this->lastName = $lastName;
        $this->firstName = $firstName;
        $this->additional = $additional;
        $this->prefix = $prefix;
        $this->suffix = $suffix;
    }

    public static function fromVcfString(string $value): self
    {
        @list(
            $firstName,
            $additional,
            $lastName,
            $prefix,
            $suffix
        ) = explode(';', $value);
        return new self(
            ($lastName !== '') ? $lastName : null,
            ($firstName !== '') ? $firstName : null,
            ($additional !== '') ? $additional : null,
            ($prefix !== '') ? $prefix : null,
            ($suffix !== '') ? $suffix : null
        );
    }

    public function getAdditional(): ?string
    {
        return $this->additional;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getFormatter(): PropertyFormatterInterface
    {
        return new NameFormatter($this);
    }

    public static function getParser(): NodeParserInterface
    {
        return new NameParser();
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public static function getNode(): string
    {
        return 'N';
    }

    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    public function getSuffix(): ?string
    {
        return $this->suffix;
    }

    public function isAllowedMultipleTimes(): bool
    {
        return false;
    }
}
