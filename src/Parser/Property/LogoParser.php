<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Parser\Property;

use JeroenDesloovere\VCard\Property\NodeInterface;
use JeroenDesloovere\VCard\Property\Logo;

final class LogoParser extends PropertyParser implements NodeParserInterface
{
    public function parseLine(string $value, array $parameters = []): NodeInterface
    {
        return new Logo($value);
    }
}
