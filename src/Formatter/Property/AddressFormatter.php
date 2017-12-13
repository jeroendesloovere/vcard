<?php

namespace JeroenDesloovere\VCard\Formatter\Property;

use JeroenDesloovere\VCard\Property\Address;

class AddressFormatter extends PropertyFormatter implements PropertyFormatterInterface
{
    /**
     * @var Address
     */
    protected $address;

    public function __construct(Address $address)
    {
        $this->address = $address;
    }

    public function getVcfString(): string
    {
        return $this->address->getNode() . ':' . $this->escape(
            $this->address->getPostOfficeBox()
            . ';' . $this->address->getExtendedAddress()
            . ';' . $this->address->getStreetAddress()
            . ';' . $this->address->getLocality()
            . ';' . $this->address->getRegion()
            . ';' . $this->address->getPostalCode()
            . ';' . $this->address->getCountryName()
        );
    }
}
