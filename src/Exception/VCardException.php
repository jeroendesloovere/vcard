<?php

declare(strict_types=1);

namespace Dilone\VCard\Exception;

use Dilone\VCard\Property\NodeInterface;
use Dilone\VCard\Property\Parameter\Kind;
use Dilone\VCard\Property\Parameter\PropertyParameterInterface;
use Dilone\VCard\Property\PropertyInterface;
use Dilone\VCard\VCard;

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

    public static function forNotAllowedPropertyOnVCardKind(PropertyInterface $property, Kind $kind): self
    {
        return new self(
            'The property "' . get_class($property) . '" you are trying to add can only be added
             to vCard\'s of the ' . $kind->__toString() . ' kind.'
        );
    }

    public static function forNotSupportedNode(NodeInterface $node): self
    {
        return new self(
            'The node "' . get_class($node) . '" you are trying to add is not supported. Possible values are: '
                . implode(', ', VCard::POSSIBLE_VALUES)
        );
    }
}
