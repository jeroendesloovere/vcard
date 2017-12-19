<?php

namespace JeroenDesloovere\VCard\Parser\Property;

use JeroenDesloovere\VCard\Property\Name;
use JeroenDesloovere\VCard\Property\NodeInterface;

final class NameParser implements NodeParserInterface
{
    public function parseLine(string $value, array $parameters = []): NodeInterface
    {
        @list($firstName, $additional, $lastName, $prefix, $suffix) = explode(';', $value);

        return new Name(
            ($lastName !== '') ? $lastName : null,
            ($firstName !== '') ? $firstName : null,
            ($additional !== '') ? $additional : null,
            ($prefix !== '') ? $prefix : null,
            ($suffix !== '') ? $suffix : null
        );
    }
}
