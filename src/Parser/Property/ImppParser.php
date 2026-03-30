<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Parser\Property;

use JeroenDesloovere\VCard\Property\Impp;
use JeroenDesloovere\VCard\Property\NodeInterface;
use JeroenDesloovere\VCard\Property\Parameter\Type;

final class ImppParser extends PropertyParser implements NodeParserInterface
{
    public function parseVcfString(string $value, array $parameters = []): NodeInterface
    {
        $impp = new Impp($value);

        if (array_key_exists(Type::getNode(), $parameters)) {
            $impp->setType($parameters[Type::getNode()]);
        }

        return $impp;
    }
}
