<?php

namespace JeroenDesloovere\VCard\Exception;

class PropertyException extends VCardException
{
    public static function forEmptyProperty(): self
    {
        return new self('The property you are trying to add is empty.');
    }
}
