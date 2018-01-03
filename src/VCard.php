<?php

namespace JeroenDesloovere\VCard;

use JeroenDesloovere\VCard\Exception\VCardException;
use JeroenDesloovere\VCard\Property\Address;
use JeroenDesloovere\VCard\Property\FullName;
use JeroenDesloovere\VCard\Property\Gender;
use JeroenDesloovere\VCard\Property\Name;
use JeroenDesloovere\VCard\Property\NodeInterface;
use JeroenDesloovere\VCard\Property\Note;
use JeroenDesloovere\VCard\Property\Parameter\Kind;
use JeroenDesloovere\VCard\Property\Parameter\PropertyParameterInterface;
use JeroenDesloovere\VCard\Property\Parameter\Revision;
use JeroenDesloovere\VCard\Property\Parameter\Type;
use JeroenDesloovere\VCard\Property\Parameter\Version;
use JeroenDesloovere\VCard\Property\PropertyInterface;

final class VCard
{
    public const POSSIBLE_VALUES = [
        // All possible property parameters
        Version::class,
        Revision::class,
        Kind::class,
        Type::class,
        // All possible properties
        Name::class,
        FullName::class,
        Address::class,
        Note::class,
        Gender::class,
    ];

    /**
     * @var PropertyParameterInterface[]
     */
    private $parameters = [];

    /**
     * @var PropertyInterface[]
     */
    private $properties = [];

    public function __construct(Kind $kind = null)
    {
        $this->add($kind ?? Kind::individual());
    }

    public function add(NodeInterface $node): self
    {
        if (array_key_exists(get_class($node), self::POSSIBLE_VALUES)) {
            throw VCardException::forNotAllowedNode($node);
        }

        if ($node instanceof PropertyInterface) {
            $this->addProperty($node);
        } elseif($node instanceof PropertyParameterInterface) {
            $this->addPropertyParameter($node);
        }

        return $this;
    }

    private function addProperty(PropertyInterface $property): void
    {
        if (!$property->isAllowedMultipleTimes() && $this->hasPropertyByClassName(get_class($property))) {
            throw VCardException::forExistingProperty($property);
        }

        $this->properties[] = $property;
    }

    private function addPropertyParameter(PropertyParameterInterface $propertyParameter): void
    {
        if ($this->hasPropertyByClassName(get_class($propertyParameter))) {
            throw VCardException::forExistingPropertyParameter($propertyParameter);
        }

        $this->parameters[] = $propertyParameter;
    }

    public function getKind(): Kind
    {
        return $this->getParameters(Kind::class)[0];
    }

    public function getParameters(string $filterByPropertyParameterClass = null): array
    {
        if ($filterByPropertyParameterClass === null) {
            return $this->parameters;
        }

        return array_filter($this->parameters, function (PropertyParameterInterface $parameter) use ($filterByPropertyParameterClass) {
            return $parameter instanceof $filterByPropertyParameterClass;
        });
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

    public function hasParameterByClassName(string $filterByParameterClass): bool
    {
        return count($this->getParameters($filterByParameterClass)) > 0;
    }

    public function hasPropertyByClassName(string $filterByPropertyClass): bool
    {
        return count($this->getProperties($filterByPropertyClass)) > 0;
    }
}
