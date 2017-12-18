<?php

namespace JeroenDesloovere\VCard\Exception;

use JeroenDesloovere\VCard\Property\PropertyInterface;

class VCardException extends \Exception
{
    public static function forExistingProperty(PropertyInterface $property): self
    {
        return new self(
            'The property "' . get_class($property) . '" you are trying to add can only be added once.'
        );
    }
}
