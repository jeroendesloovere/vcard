<?php

namespace JeroenDesloovere\VCard\Exception;

final class PropertyException extends VCardException
{
    public static function forEmptyProperty(): self
    {
        return new self('The property you are trying to add is empty.');
    }

    public static function forWrongValue(string $value, array $possibleValues): self
    {
        return new self(
            'The given value "' . $value . '" is not allowed.
             Possible values are: "' . implode('", "', $possibleValues) . '"'
        );
    }
}
