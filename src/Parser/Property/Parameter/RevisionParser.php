<?php

declare(strict_types=1);

namespace Dilone\VCard\Parser\Property\Parameter;

use Dilone\VCard\Parser\Property\NodeParserInterface;
use Dilone\VCard\Property\NodeInterface;
use Dilone\VCard\Property\Parameter\Revision;

final class RevisionParser implements NodeParserInterface
{
    public function parseVcfString(string $value, array $parameters = []): NodeInterface
    {
        return new Revision(new \DateTime($value));
    }
}
