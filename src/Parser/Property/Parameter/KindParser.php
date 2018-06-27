<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Parser\Property\Parameter;

use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Property\NodeInterface;
use JeroenDesloovere\VCard\Property\Parameter\Kind;

final class KindParser implements NodeParserInterface
{
    public function parseVcfString(string $value, array $parameters = []): NodeInterface
    {
        return new Kind($value);
    }
}
