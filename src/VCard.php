<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard;

use JeroenDesloovere\VCard\Exception\VCardException;
use JeroenDesloovere\VCard\Property\Address;
use JeroenDesloovere\VCard\Property\Anniversary;
use JeroenDesloovere\VCard\Property\Birthdate;
use JeroenDesloovere\VCard\Property\Email;
use JeroenDesloovere\VCard\Property\FullName;
use JeroenDesloovere\VCard\Property\Gender;
use JeroenDesloovere\VCard\Property\Logo;
use JeroenDesloovere\VCard\Property\Name;
use JeroenDesloovere\VCard\Property\Nickname;
use JeroenDesloovere\VCard\Property\NodeInterface;
use JeroenDesloovere\VCard\Property\Note;
use JeroenDesloovere\VCard\Property\Parameter\Kind;
use JeroenDesloovere\VCard\Property\Parameter\PropertyParameterInterface;
use JeroenDesloovere\VCard\Property\Parameter\Revision;
use JeroenDesloovere\VCard\Property\Parameter\Type;
use JeroenDesloovere\VCard\Property\Parameter\Version;
use JeroenDesloovere\VCard\Property\Photo;
use JeroenDesloovere\VCard\Property\PropertyInterface;
use JeroenDesloovere\VCard\Property\Telephone;
use JeroenDesloovere\VCard\Property\Title;

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
        Nickname::class,
        Title::class,
        Birthdate::class,
        Anniversary::class,
        Email::class,
        Photo::class,
        Logo::class,
        Telephone::class,
    ];

    private const ONLY_APPLY_TO_INDIVIDUAL_KIND = [
        Birthdate::class,
        Anniversary::class,
        Gender::class,
    ];

    /** @var PropertyParameterInterface[] */
    private $parameters = [];

    /** @var PropertyInterface[] */
    private $properties = [];

    /**
     * @param Kind|null $kind
     * @throws VCardException
     */
    public function __construct(Kind $kind = null)
    {
        $this->add($kind ?? Kind::individual());
    }

    /**
     * @param NodeInterface $node
     * @return VCard
     * @throws VCardException
     */
    public function add(NodeInterface $node): self
    {
        if (!in_array(get_class($node), self::POSSIBLE_VALUES, true)) {
            throw VCardException::forNotSupportedNode($node);
        }

        switch (true) {
            case $node instanceof PropertyInterface:
                $this->addProperty($node);
                break;
            case $node instanceof PropertyParameterInterface:
                $this->addPropertyParameter($node);
                break;
        }

        return $this;
    }

    /**
     * @param PropertyInterface $property
     * @throws VCardException
     */
    private function addProperty(PropertyInterface $property): void
    {
        // Property is not allowed multiple times
        if (!$property->isAllowedMultipleTimes() && $this->hasProperty(get_class($property))) {
            throw VCardException::forExistingProperty($property);
        }

        if (!$this->getKind()->isIndividual() && $this->isAllowedIndividualKindProperty(get_class($property))) {
            throw VCardException::forNotAllowedPropertyOnVCardKind($property, Kind::individual());
        }

        $this->properties[] = $property;
    }

    /**
     * @param PropertyParameterInterface $propertyParameter
     * @throws VCardException
     */
    private function addPropertyParameter(PropertyParameterInterface $propertyParameter): void
    {
        // Parameter is not allowed multiple times
        if ($this->hasParameter(get_class($propertyParameter))) {
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

    public function getProperties(string $forPropertyClass = null): array
    {
        if ($forPropertyClass === null) {
            return $this->properties;
        }

        return array_filter($this->properties, function (PropertyInterface $property) use ($forPropertyClass) {
            return $property instanceof $forPropertyClass;
        });
    }

    public function hasParameter(string $forParameterClass): bool
    {
        return count($this->getParameters($forParameterClass)) > 0;
    }

    public function hasProperty(string $forPropertyClass): bool
    {
        return count($this->getProperties($forPropertyClass)) > 0;
    }

    private function isAllowedIndividualKindProperty(string $propertyClass): bool
    {
        return in_array($propertyClass, self::ONLY_APPLY_TO_INDIVIDUAL_KIND, true);
    }
}
