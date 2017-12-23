<?php

namespace JeroenDesloovere\VCard\Exception;

final class ParserException extends VCardException
{
    public static function forUnreadableVCard(string $file): self
    {
        return new self(sprintf("File '%s' is not readable, or doesn't exist.", $file));
    }
}
