<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Parser\Property;

use JeroenDesloovere\VCard\Property\NodeInterface;
use JeroenDesloovere\VCard\Property\Parameter\Revision;

final class RevisionParser implements NodeParserInterface
{
    public function parseLine(string $value, array $parameters = []): NodeInterface
    {
        return new Revision(new \DateTime($value));
    }
}
