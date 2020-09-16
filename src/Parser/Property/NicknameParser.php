<?php

declare(strict_types=1);

namespace Dilone\VCard\Parser\Property;

use Dilone\VCard\Property\Nickname;
use Dilone\VCard\Property\NodeInterface;

final class NicknameParser implements NodeParserInterface
{
    public function parseVcfString(string $value, array $parameters = []): NodeInterface
    {
        return new Nickname($value);
    }
}
