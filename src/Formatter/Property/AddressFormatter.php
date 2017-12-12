<?php

namespace JeroenDesloovere\VCard\Formatter\Property;

use JeroenDesloovere\VCard\Property\Address;
use JeroenDesloovere\VCard\Property\PropertyInterface;

class AddressFormatter extends PropertyFormatter implements PropertyFormatterInterface
{
    /**
     * @param Address|PropertyInterface $address
     * @return string
     */
    public function convertToVcfString(PropertyInterface $address): string
    {
        return $address->getNode().':'.$this->escape(
            $address->getPostOfficeBox().';'.$address->getExtendedAddress().';'.$address->getStreetAddress().';'.$address->getLocality().';'.$address->getRegion().';'.$address->getPostalCode().';'.$address->getCountryName()
        );
    }
}
