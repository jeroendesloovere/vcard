<?php

namespace JeroenDesloovere\VCard\Exception;

class FormatterException extends VCardException
{
    public static function forUnreadableVCard(string $input): self
    {
        return new self('The given input "' . $input . '" is not a VCard.');
    }
}
