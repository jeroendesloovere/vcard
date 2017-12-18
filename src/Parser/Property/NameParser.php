<?php

namespace JeroenDesloovere\VCard\Parser\Property;

use JeroenDesloovere\VCard\Property\Name;
use JeroenDesloovere\VCard\Property\NodeInterface;

class NameParser extends PropertyParser implements NodeParserInterface
{
    public function parseLine(string $value, array $parameters = []): NodeInterface
    {
        return Name::fromVcfString($value);
    }
}
