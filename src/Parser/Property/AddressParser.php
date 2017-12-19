<?php

namespace JeroenDesloovere\VCard\Parser\Property;

use JeroenDesloovere\VCard\Property\Address;
use JeroenDesloovere\VCard\Property\NodeInterface;
use JeroenDesloovere\VCard\Property\Parameter\Type;

final class AddressParser implements NodeParserInterface
{
    public function parseLine(string $value, array $parameters = []): NodeInterface
    {
        $address = $this->parseAddress($value);

        if (array_key_exists(Type::getNode(), $parameters)) {
            $address->setType($parameters[Type::getNode()]);
        }

        return $address;
    }

    private function parseAddress(string $value): Address
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

        $this->convertEmptyStringToNull([
            $postOfficeBox,
            $extendedAddress,
            $streetAddress,
            $locality,
            $region,
            $postalCode,
            $countryName
        ]);

        return new Address($postOfficeBox, $extendedAddress, $streetAddress, $locality, $region, $postalCode, $countryName);
    }

    private function convertEmptyStringToNull(array $values): void
    {
        foreach ($values as &$value) {
            if ($value === '') {
                $value = null;
            }
        }
    }
}
