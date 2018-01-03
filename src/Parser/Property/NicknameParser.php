<?php

namespace JeroenDesloovere\VCard\Parser\Property;

use JeroenDesloovere\VCard\Property\Nickname;
use JeroenDesloovere\VCard\Property\NodeInterface;

final class NicknameParser implements NodeParserInterface
{
    public function parseLine(string $value, array $parameters = []): NodeInterface
    {
        return new Nickname($value);
    }
}
