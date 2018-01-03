<?php

namespace JeroenDesloovere\VCard\Exception;

use JeroenDesloovere\VCard\Property\NodeInterface;
use JeroenDesloovere\VCard\Property\Parameter\Kind;
use JeroenDesloovere\VCard\Property\Parameter\PropertyParameterInterface;
use JeroenDesloovere\VCard\Property\PropertyInterface;
use JeroenDesloovere\VCard\VCard;

class VCardException extends \Exception
{
    public static function forExistingProperty(PropertyInterface $property): self
    {
        return new self(
            'The property "' . get_class($property) . '" you are trying to add can only be added once.'
        );
    }

    public static function forExistingPropertyParameter(PropertyParameterInterface $parameter): self
    {
        return new self(
            'The property parameter "' . get_class($parameter) . '" you are trying to add can only be added once.'
        );
    }

    public static function forNotAllowedNode(NodeInterface $node): self
    {
        return new self(
            'The node "' . get_class($node) . '" you are trying to add is not allowed. Possible values are: '
            . implode(', ', VCard::POSSIBLE_VALUES)
        );
    }

    public static function forNotAllowedPropertyOnVCardKind(PropertyInterface $property, Kind $kind): self
    {
        return new self(
            'The property "' . get_class($property) . '" you are trying to add can only be added to vCard\'s of the ' . $kind->__toString() . ' kind.'
        );
    }
}
