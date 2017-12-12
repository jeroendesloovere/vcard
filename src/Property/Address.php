<?php

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Formatter\Property\AddressFormatter;
use JeroenDesloovere\VCard\Formatter\Property\PropertyFormatterInterface;
use JeroenDesloovere\VCard\Property\Parameter\Type;

class Address implements PropertyInterface
{
    /** @var null|string - The country name in your own language, e.g.: belgië */
    private $countryName;

    /** @var null|string - e.g.: apartment or suite number */
    private $extendedAddress;

    /** @var null|string - e.g.: city */
    private $locality;

    /** @var null|string */
    private $postalCode;

    /** @var null|string */
    private $postOfficeBox;

    /** @var null|string  - e.g.: state or province */
    private $region;

    /** @var null|string */
    private $streetAddress;

    /** @var Type */
    private $type;

    /**
     * Address constructor.
     *
     * @param null|string $postOfficeBox
     * @param null|string $extendedAddress
     * @param null|string $streetAddress
     * @param null|string $locality
     * @param null|string $region
     * @param null|string $postalCode
     * @param null|string $countryName
     * @param Type|null   $type
     */
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
        $this->postOfficeBox = $postOfficeBox;
        $this->extendedAddress = $extendedAddress;
        $this->streetAddress = $streetAddress;
        $this->locality = $locality;
        $this->region = $region;
        $this->postalCode = $postalCode;
        $this->countryName = $countryName;
        $this->type = $type ?? Type::home();
    }

    /**
     * @return null|string
     */
    public function getCountryName(): ?string
    {
        return $this->countryName;
    }

    /**
     * @return null|string
     */
    public function getExtendedAddress(): ?string
    {
        return $this->extendedAddress;
    }

    /**
     * @return PropertyFormatterInterface
     */
    public function getFormatter(): PropertyFormatterInterface
    {
        return new AddressFormatter();
    }

    /**
     * @return null|string
     */
    public function getLocality(): ?string
    {
        return $this->locality;
    }

    /**
     * @return string
     */
    public function getNode(): string
    {
        return 'ADR';
    }

    /**
     * @return null|string
     */
    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    /**
     * @return null|string
     */
    public function getPostOfficeBox(): ?string
    {
        return $this->postOfficeBox;
    }

    /**
     * @return null|string
     */
    public function getRegion(): ?string
    {
        return $this->region;
    }

    /**
     * @return null|string
     */
    public function getStreetAddress(): ?string
    {
        return $this->streetAddress;
    }

    /**
     * @return Type
     */
    public function getType(): Type
    {
        return $this->type;
    }
}