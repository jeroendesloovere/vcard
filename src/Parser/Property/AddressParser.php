<?php

namespace JeroenDesloovere\VCard\Parser\Property;

use JeroenDesloovere\VCard\Property\Address;
use JeroenDesloovere\VCard\Property\NodeInterface;
use JeroenDesloovere\VCard\Property\Parameter\Type;

class AddressParser implements NodeParserInterface
{
    public function parseLine(string $value, array $parameters = []): NodeInterface
    {
        @list(
            $postOfficeBox,
            $extendedAddress,
            $streetAddress,
            $locality,
            $region,
            $postalCode,
            $countryName
        ) = explode(';', $value);

        $address = new Address(
            ($postOfficeBox !== '') ? $postOfficeBox : null,
            ($extendedAddress !== '') ? $extendedAddress : null,
            ($streetAddress !== '') ? $streetAddress : null,
            ($locality !== '') ? $locality : null,
            ($region !== '') ? $region : null,
            ($postalCode !== '') ? $postalCode : null,
            ($countryName !== '') ? $countryName : null
        );

        if (array_key_exists(Type::getNode(), $parameters)) {
            $address->setType($parameters[Type::getNode()]);
        }

        return $address;
    }
}
