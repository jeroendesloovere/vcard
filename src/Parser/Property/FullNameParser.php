<?php

declare(strict_types=1);

namespace Dilone\VCard\Parser\Property;

use Dilone\VCard\Property\FullName;
use Dilone\VCard\Property\NodeInterface;

final class FullNameParser implements NodeParserInterface
{
    public function parseVcfString(string $value, array $parameters = []): NodeInterface
    {
        return new FullName($value);
    }
}
