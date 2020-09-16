<?php

declare(strict_types=1);

namespace Dilone\VCard\Formatter\Property;

use Dilone\VCard\Property\Address;

final class AddressFormatter extends NodeFormatter implements NodeFormatterInterface
{
    /** @var Address */
    protected $address;

    public function __construct(Address $address)
    {
        $this->address = $address;
    }

    public function getVcfString(): string
    {
        return
            $this->address::getNode()
            . ';TYPE=' . $this->address->getType()->__toString()
            . ':' . $this->escape(implode(';', array(
                $this->address->getPostOfficeBox(),
                $this->address->getExtendedAddress(),
                $this->address->getStreetAddress(),
                $this->address->getLocality(),
                $this->address->getRegion(),
                $this->address->getPostalCode(),
                $this->address->getCountryName()
            )));
    }
}
