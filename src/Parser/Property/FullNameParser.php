<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Parser\Property;

use JeroenDesloovere\VCard\Property\FullName;
use JeroenDesloovere\VCard\Property\NodeInterface;

final class FullNameParser implements NodeParserInterface
{
    public function parseVcfString(string $value, array $parameters = []): NodeInterface
    {
        return new FullName($value);
    }
}
