<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Parser\Property;

use JeroenDesloovere\VCard\Property\NodeInterface;
use JeroenDesloovere\VCard\Property\Photo;

final class PhotoParser extends PropertyParser implements NodeParserInterface
{
    public function parseLine(string $value, array $parameters = []): NodeInterface
    {
        return new Photo($value);
    }
}
