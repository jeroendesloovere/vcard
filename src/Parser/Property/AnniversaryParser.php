<?php

declare(strict_types=1);

namespace Dilone\VCard\Parser\Property;

use Dilone\VCard\Property\Anniversary;
use Dilone\VCard\Property\NodeInterface;

final class AnniversaryParser implements NodeParserInterface
{
    public function parseVcfString(string $value, array $parameters = []): NodeInterface
    {
        return new Anniversary(new \DateTime($value));
    }
}
