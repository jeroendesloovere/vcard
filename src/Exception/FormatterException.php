<?php

declare(strict_types=1);

namespace Dilone\VCard\Exception;

final class FormatterException extends VCardException
{
    public static function forUnreadableVCard(string $input): self
    {
        return new self('The given input "' . $input . '" is not a VCard.');
    }
}
