<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Parser\Property;

use JeroenDesloovere\VCard\Property\Birthdate;
use JeroenDesloovere\VCard\Property\NodeInterface;

final class BirthdateParser implements NodeParserInterface
{
    public function parseVcfString(string $value, array $parameters = []): NodeInterface
    {
        return new Birthdate(new \DateTime($value));
    }
}
