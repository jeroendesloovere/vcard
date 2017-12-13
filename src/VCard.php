<?php

namespace JeroenDesloovere\VCard;

use JeroenDesloovere\VCard\Property\Parameter\Kind;
use JeroenDesloovere\VCard\Property\PropertyInterface;

class VCard
{
    /**
     * @var Kind - Possible values are: Group, Individual, Location or Organization
     */
    private $kind;

    /**
     * @var PropertyInterface[]
     */
    private $properties = [];

    public function __construct(Kind $kind = null)
    {
        $this->setKind($kind ?? Kind::individual());
    }

    public function add(PropertyInterface $property): self
    {
        if (!$property->isAllowedMultipleTimes() && $this->hasProperty(get_class($property))) {
            throw new \RuntimeException(
                'The property "' . get_class($property) . '" you are trying to add can only be added once.'
            );
        }

        $this->properties[] = $property;

        return $this;
    }

    public function getKind(): Kind
    {
        return $this->kind;
    }

    public function getProperties(string $filterByPropertyClass = null): array
    {
        if ($filterByPropertyClass === null) {
            return $this->properties;
        }

        return array_filter($this->properties, function (PropertyInterface $property) use ($filterByPropertyClass) {
            return $property instanceof $filterByPropertyClass;
        });
    }

    public function hasProperty(string $filterByPropertyClass): bool
    {
        return count($this->getProperties($filterByPropertyClass)) > 0;
    }

    public function setKind(Kind $kind): void
    {
        $this->kind = $kind;
    }
}
