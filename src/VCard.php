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
use JeroenDesloovere\VCard\Property\Role;
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
        Role::class,
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
     * @param Version|null $version
     * @throws VCardException
     */
    public function __construct(Kind $kind = null, Version $version = null)
    {
        $this->add($version ?? Version::version4());
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
        $kind = $this->getParameters(Kind::class);

        return reset($kind);
    }

    public function getParameters(string $filterByPropertyParameterClass = null): array
    {
        if ($filterByPropertyParameterClass === null) {
            $array = $this->parameters;
            $found = $others = [];
            foreach ($array as $value) {
                if ($value instanceof Version) {
                    $found[] = $value;
                } else {
                    $others[] = $value;
                }
            }
            $array = array_merge($found, $others);
            $this->parameters = $array;

            return $this->parameters;
        }

        return array_filter($this->parameters, function (PropertyParameterInterface $parameter) use ($filterByPropertyParameterClass) {
            return $parameter instanceof $filterByPropertyParameterClass;
        });
    }

    /**
     * @param string $forPropertyClass
     * @return array
     * @throws VCardException
     */
    public function getProperties(string $forPropertyClass = null): array
    {
        if ($forPropertyClass === null) {
            $array = $this->properties;
            //make empty array var for each required Property or Property you need to work with
            $found_fullname = $found_name = $others = [];
            foreach ($array as $value) {
                //search if property exist and add it to the defined array var, else add it to the others array
                if ($value instanceof FullName) {
                    $found_fullname[] = $value;
                } elseif ($value instanceof Name) {
                    $found_name[] = $value;
                } else {
                    $others[] = $value;
                }
            }
            //check for empty and throw exception, if it can not be generated by existing fields
            if (count($found_fullname) == 0) {
                if (count($found_name) == 0) {
                    throw VCardException::forRequiredProperty(new FullName('NoName'));
                } else {
                    $found_fullname[] = new FullName(implode(' ', array_filter(array($found_name[0]->getPrefix(), $found_name[0]->getFirstName(), $found_name[0]->getAdditional(), $found_name[0]->getLastName(), $found_name[0]->getSuffix()))));
                }
            }
            $array = array_merge($found_fullname, $found_name, $others);
            $this->properties = $array;

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
