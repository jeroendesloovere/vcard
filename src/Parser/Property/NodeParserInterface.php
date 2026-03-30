<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Parser\Property;

use JeroenDesloovere\VCard\Property\NodeInterface;

interface NodeParserInterface
{
    public function parseVcfString(string $value, array $parameters = []): NodeInterface;
}
