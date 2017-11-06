<?php

namespace JeroenDesloovere\VCard\Property;

class Address implements PropertyInterface
{
    /** @var null|string */
    private $locality;

    /** @var null|string */
    private $countryName;

    /** @var null|string */
    private $region;

    /** @var null|string */
    private $streetAddress;

    /** @var Type */
    private $type;

    /** @var null|string */
    private $postalCode;

    public function __construct(
        Type $type,
        ?string $streetAddress = null,
        ?string $locality = null,
        ?string $postalCode = null,
        ?string $countryName = null,
        ?string $region = null
    ) {
        $this->type = $type;
        $this->streetAddress = $streetAddress;
        $this->locality = $locality;
        $this->postalCode = $postalCode;
        $this->countryName = $countryName;
        $this->region = $region;
    }
}