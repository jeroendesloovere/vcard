<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Parser\Property;

use JeroenDesloovere\VCard\Property\ProdId;
use JeroenDesloovere\VCard\Property\NodeInterface;

final class ProdIdParser implements NodeParserInterface
{
    public function parseVcfString(string $value, array $parameters = []): NodeInterface
    {
        return new ProdId($value);
    }
}
