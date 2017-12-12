<?php

namespace JeroenDesloovere\VCard;

use JeroenDesloovere\VCard\Property\Parameter\Kind;
use JeroenDesloovere\VCard\Property\PropertyInterface;

class VCard
{
    /** @var array */
    private $properties;

    /** @var Kind */
    private $kind;

    /**
     * VCard constructor.
     *
     * @param Kind|null $kind
     */
    public function __construct(Kind $kind = null)
    {
        if ($kind === null) {
            $kind = Kind::individual();
        }

        $this->kind = $kind;
    }

    /**
     * @param PropertyInterface $property
     *
     * @return VCard
     */
    public function add(PropertyInterface $property): self
    {
        $this->properties[] = $property;

        return $this;
    }

    /**
     * @return Kind
     */
    public function getKind(): Kind
    {
        return $this->kind;
    }

    /**
     * @return array
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @param PropertyInterface $propertyClass
     *
     * @return array
     */
    public function getPropertiesByProperty(PropertyInterface $propertyClass): array
    {
        $properties = [];

        foreach ($this->properties as $property) {
            if ($property instanceof $propertyClass) {
                $properties[] = $property;
            }
        }

        return $properties;
    }

    /**
     * @param PropertyInterface $propertyClass
     *
     * @return bool
     */
    public function hasProperty(PropertyInterface $propertyClass): bool
    {
        foreach ($this->properties as $property) {
            if ($property instanceof $propertyClass) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Kind $kind
     */
    public function setKind(Kind $kind): void
    {
        $this->kind = $kind;
    }
}