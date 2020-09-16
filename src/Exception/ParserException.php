<?php

declare(strict_types=1);

namespace Dilone\VCard\Exception;

final class ParserException extends VCardException
{
    public static function forUnreadableVCard(string $file): self
    {
        return new self(sprintf('File "%s" is not readable, or doesn\'t exist.', $file));
    }
}
