<?php

namespace JeroenDesloovere\VCard\Exception;

class VCardException extends \Exception
{
    public static function forUnreadableFile(string $file): self
    {
        return new self(
            sprintf("File %s is not readable, or doesn't exist.", $file)
        );
    }

    public static function forWrongValue(string $value, array $possibleValues): self
    {
        return new self(
            'The given type "' . $value . '" is not allowed. Possible values are: ' . implode(', ', $possibleValues)
        );
    }
}
