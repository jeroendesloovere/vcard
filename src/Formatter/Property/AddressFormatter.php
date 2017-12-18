<?php

namespace JeroenDesloovere\VCard\Formatter\Property;

use JeroenDesloovere\VCard\Property\Address;

class AddressFormatter extends NodeFormatter implements NodeFormatterInterface
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
        $string = $this->address->getNode();
        $string .= ';TYPE=' . $this->address->getType()->__toString();
        $string .=':' . $this->escape(
            $this->address->getPostOfficeBox()
            . ';' . $this->address->getExtendedAddress()
            . ';' . $this->address->getStreetAddress()
            . ';' . $this->address->getLocality()
            . ';' . $this->address->getRegion()
            . ';' . $this->address->getPostalCode()
            . ';' . $this->address->getCountryName()
        );

        return $string;
    }
}
