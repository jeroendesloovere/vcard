<?php

namespace JeroenDesloovere\VCard\Parser\Property;

use JeroenDesloovere\VCard\Property\NodeInterface;

interface NodeParserInterface
{
    public function parseLine(string $value, array $parameters = []): NodeInterface;
}
