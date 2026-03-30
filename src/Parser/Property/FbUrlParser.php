<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Parser\Property;

use JeroenDesloovere\VCard\Property\FbUrl;
use JeroenDesloovere\VCard\Property\NodeInterface;
use JeroenDesloovere\VCard\Property\Parameter\Type;

final class FbUrlParser extends PropertyParser implements NodeParserInterface
{
    public function parseVcfString(string $value, array $parameters = []): NodeInterface
    {
        $fbUrl = new FbUrl($value);

        if (array_key_exists(Type::getNode(), $parameters)) {
            $fbUrl->setType($parameters[Type::getNode()]);
        }

        return $fbUrl;
    }
}
