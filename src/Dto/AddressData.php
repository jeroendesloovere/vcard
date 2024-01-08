<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Dto;

final class AddressData
{
    public function __construct(
        private readonly string $name,
        private readonly string $extended,
        private readonly string $street,
        private readonly string $city,
        private readonly string $region,
        private readonly string $zip,
        private readonly string $country,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getExtended(): string
    {
        return $this->extended;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getRegion(): string
    {
        return $this->region;
    }

    public function getZip(): string
    {
        return $this->zip;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'extended' => $this->extended,
            'street' => $this->street,
            'city' => $this->city,
            'region' => $this->region,
            'zip' => $this->zip,
            'country' => $this->country,
        ];
    }
}
