<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Parser\Property;

use JeroenDesloovere\VCard\Property\Nickname;
use JeroenDesloovere\VCard\Property\NodeInterface;

final class NicknameParser implements NodeParserInterface
{
    public function parseVcfString(string $value, array $parameters = []): NodeInterface
    {
        return new Nickname($value);
    }
}
