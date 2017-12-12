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

    public function __construct(Kind $kind = null)
    {
        if ($kind === null) {
            $kind = Kind::individual();
        }

        $this->kind = $kind;
    }

    public function add(PropertyInterface $property): self
    {
        $this->properties[] = $property;

        return $this;
    }

    public function getKind(): Kind
    {
        return $this->kind;
    }

    public function getProperties(): array
    {
        return $this->properties;
    }

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

    public function hasProperty(PropertyInterface $propertyClass): bool
    {
        foreach ($this->properties as $property) {
            if ($property instanceof $propertyClass) {
                return true;
            }
        }

        return false;
    }

    public function setKind(Kind $kind)
    {
        $this->kind = $kind;
    }
}