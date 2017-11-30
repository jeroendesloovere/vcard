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
        // @todo: I'm using "countryName" like "BE", but actually it should be written fullout and in the language the vcard is defined.
        // so f.e.: "belgië"
        return 'ADR:' . $this->escape(
            $address->getPostOfficeBox()
            . ';' . $address->getExtendedAddress()
            . ';' . $address->getStreetAddress()
            . ';' . $address->getLocality()
            . ';' . $address->getRegion()
            . ';' . $address->getPostalCode()
            . ';' . $address->getCountryName()
        );
    }
}