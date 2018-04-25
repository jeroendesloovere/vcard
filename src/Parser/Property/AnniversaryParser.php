<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Parser\Property;

use JeroenDesloovere\VCard\Property\Anniversary;
use JeroenDesloovere\VCard\Property\NodeInterface;

final class AnniversaryParser implements NodeParserInterface
{
    public function parseLine(string $value, array $parameters = []): NodeInterface
    {
        return new Anniversary(new \DateTime($value));
    }
}
