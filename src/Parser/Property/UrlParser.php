<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Parser\Property;

use JeroenDesloovere\VCard\Property\NodeInterface;
use JeroenDesloovere\VCard\Property\Url;

final class UrlParser implements NodeParserInterface
{
    public function parseVcfString(string $value, array $parameters = []): NodeInterface
    {
        return new Url($value);
    }
}