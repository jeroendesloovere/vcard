<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Exception\PropertyException;
use JeroenDesloovere\VCard\Formatter\Property\AddressFormatter;
use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Parser\Property\AddressParser;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Property\Parameter\Type;

final class Address implements PropertyInterface, NodeInterface
{
    /** @var null|string */
    private $postOfficeBox;

    /** @var null|string - e.g.: apartment or suite number */
    private $extendedAddress;

    /** @var null|string */
    private $streetAddress;

    /** @var null|string - e.g.: city */
    private $locality;

    /** @var null|string  - e.g.: state or province */
    private $region;

    /** @var null|string */
    private $postalCode;

    /** @var null|string - The country name in your own language, e.g.: belgië */
    private $countryName;

    /** @var Type */
    private $type;

    public function __construct(
        ?string $postOfficeBox = null,
        ?string $extendedAddress = null,
        ?string $streetAddress = null,
        ?string $locality = null,
        ?string $region = null,
        ?string $postalCode = null,
        ?string $countryName = null,
        Type $type = null
    ) {
        if ($postOfficeBox === null && $extendedAddress === null && $streetAddress === null && $locality === null
            && $region === null && $postalCode === null && $countryName === null && $type === null
        ) {
            throw PropertyException::forEmptyProperty();
        }

        $this->postOfficeBox = $postOfficeBox;
        $this->extendedAddress = $extendedAddress;
        $this->streetAddress = $streetAddress;
        $this->locality = $locality;
        $this->region = $region;
        $this->postalCode = $postalCode;
        $this->countryName = $countryName;
        $this->type = $type ?? Type::home();
    }

    public function getFormatter(): NodeFormatterInterface
    {
        return new AddressFormatter($this);
    }

    public static function getNode(): string
    {
        return 'ADR';
    }

    public static function getParser(): NodeParserInterface
    {
        return new AddressParser();
    }

    public function isAllowedMultipleTimes(): bool
    {
        return true;
    }

    public function getPostOfficeBox(): ?string
    {
        return $this->postOfficeBox;
    }

    public function getExtendedAddress(): ?string
    {
        return $this->extendedAddress;
    }

    public function getStreetAddress(): ?string
    {
        return $this->streetAddress;
    }

    public function getLocality(): ?string
    {
        return $this->locality;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function getCountryName(): ?string
    {
        return $this->countryName;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function setType(Type $type)
    {
        $this->type = $type;
    }
}
