<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Parser\Property;

use JeroenDesloovere\VCard\Property\Org;
use JeroenDesloovere\VCard\Property\NodeInterface;

final class OrgParser implements NodeParserInterface
{
    public function parseVcfString(string $value, array $parameters = []): NodeInterface
    {
        $parts = explode(';', $value);
        $organizationName = array_shift($parts);
        return new Org($organizationName, ...$parts);
    }
}
