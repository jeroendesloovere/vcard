<?php

namespace JeroenDesloovere\VCard\Model;

use JeroenDesloovere\VCard\Exception\InvalidVersionException;

/**
 * Class VCardAddress
 *
 * @package JeroenDesloovere\VCard\Model
 */
class VCardAddress
{
    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $extended;

    /**
     * @var string|null
     */
    protected $street;

    /**
     * @var string|null
     */
    protected $locality;

    /**
     * @var string|null
     */
    protected $region;

    /**
     * @var string|null
     */
    protected $postalCode;

    /**
     * @var string|null
     */
    protected $country;

    /**
     * @var string|null
     */
    protected $label;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getExtended(): ?string
    {
        return $this->extended;
    }

    /**
     * @param string|null $extended
     */
    public function setExtended(?string $extended): void
    {
        $this->extended = $extended;
    }

    /**
     * @return string|null
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * @param string|null $street
     */
    public function setStreet(?string $street): void
    {
        $this->street = $street;
    }

    /**
     * @return string|null
     */
    public function getLocality(): ?string
    {
        return $this->locality;
    }

    /**
     * @param string|null $locality
     */
    public function setLocality(?string $locality): void
    {
        $this->locality = $locality;
    }

    /**
     * @return string|null
     */
    public function getRegion(): ?string
    {
        return $this->region;
    }

    /**
     * @param string|null $region
     */
    public function setRegion(?string $region): void
    {
        $this->region = $region;
    }

    /**
     * @return string|null
     */
    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    /**
     * @param string|null $postalCode
     */
    public function setPostalCode(?string $postalCode): void
    {
        $this->postalCode = $postalCode;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string|null $country
     */
    public function setCountry(?string $country): void
    {
        $this->country = $country;
    }

    /**
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @param string|null $label
     */
    public function setLabel(?string $label): void
    {
        $this->label = $label;
    }

    /**
     * @param string $version
     * @param string $key
     * @param string $value
     *
     * @throws InvalidVersionException
     */
    public function parseAddress(string $version, string $key, string $value): void
    {
        if ($version === '3.0') {
            $this->setLabel($key);
        } elseif ($version === '4.0') {
            if (strpos($key, 'LABEL=') !== false) {
                $this->setLabel($key);
            }
        } else {
            throw new InvalidVersionException();
        }

        @list(
            $name,
            $extended,
            $street,
            $locality,
            $region,
            $postalCode,
            $country,
            ) = explode(';', $value);

        $this->setName($name);
        $this->setExtended($extended);
        $this->setStreet($street);
        $this->setLocality($locality);
        $this->setRegion($region);
        $this->setPostalCode($postalCode);
        $this->setCountry($country);
    }
}
