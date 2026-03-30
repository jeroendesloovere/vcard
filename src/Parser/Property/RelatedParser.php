<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Parser\Property;

use JeroenDesloovere\VCard\Property\Related;
use JeroenDesloovere\VCard\Property\NodeInterface;
use JeroenDesloovere\VCard\Property\Parameter\Type;

final class RelatedParser extends PropertyParser implements NodeParserInterface
{
    public function parseVcfString(string $value, array $parameters = []): NodeInterface
    {
        $related = new Related($value);

        if (array_key_exists(Type::getNode(), $parameters)) {
            $related->setType($parameters[Type::getNode()]);
        }

        return $related;
    }
}
