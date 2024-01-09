<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Parser\Property;

use JeroenDesloovere\VCard\Property\Gender;
use JeroenDesloovere\VCard\Property\NodeInterface;

final class GenderParser extends PropertyParser implements NodeParserInterface
{
    public function parseVcfString(string $value, array $parameters = []): NodeInterface
    {
        @list($gender, $note) = explode(';', $value, 2);

        $this->convertEmptyStringToNull($note);

        return new Gender($gender, $note);
    }
}
