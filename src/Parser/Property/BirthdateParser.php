<?php

namespace JeroenDesloovere\VCard\Parser\Property;

use JeroenDesloovere\VCard\Property\Birthdate;
use JeroenDesloovere\VCard\Property\NodeInterface;

final class BirthdateParser implements NodeParserInterface
{
    public function parseLine(string $value, array $parameters = []): NodeInterface
    {
        return new Birthdate(new \DateTime($value));
    }
}
