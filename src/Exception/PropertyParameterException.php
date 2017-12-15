<?php

namespace JeroenDesloovere\VCard\Exception;

class PropertyParameterException extends VCardException
{
    public static function forWrongValue(string $value, array $possibleValues): self
    {
        return new self(
            'The given type "' . $value . '" is not allowed. Possible values are: ' . implode(', ', $possibleValues)
        );
    }
}
