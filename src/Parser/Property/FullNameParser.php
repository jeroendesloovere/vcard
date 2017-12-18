<?php

namespace JeroenDesloovere\VCard\Parser\Property;

use JeroenDesloovere\VCard\Property\FullName;
use JeroenDesloovere\VCard\Property\NodeInterface;

class FullNameParser extends PropertyParser implements NodeParserInterface
{
    public function parseLine(string $value, array $parameters = []): NodeInterface
    {
        return new FullName($value);
    }
}
