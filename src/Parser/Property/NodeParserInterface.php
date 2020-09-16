<?php

declare(strict_types=1);

namespace Dilone\VCard\Parser\Property;

use Dilone\VCard\Property\NodeInterface;

interface NodeParserInterface
{
    public function parseVcfString(string $value, array $parameters = []): NodeInterface;
}
