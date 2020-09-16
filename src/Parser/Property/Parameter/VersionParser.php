<?php

declare(strict_types=1);

namespace Dilone\VCard\Parser\Property\Parameter;

use Dilone\VCard\Parser\Property\NodeParserInterface;
use Dilone\VCard\Property\NodeInterface;
use Dilone\VCard\Property\Parameter\Version;

final class VersionParser implements NodeParserInterface
{
    public function parseVcfString(string $value, array $parameters = []): NodeInterface
    {
        return new Version($value);
    }
}
