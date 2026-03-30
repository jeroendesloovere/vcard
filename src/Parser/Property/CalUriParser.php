<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Parser\Property;

use JeroenDesloovere\VCard\Property\CalUri;
use JeroenDesloovere\VCard\Property\NodeInterface;
use JeroenDesloovere\VCard\Property\Parameter\Type;

final class CalUriParser extends PropertyParser implements NodeParserInterface
{
    public function parseVcfString(string $value, array $parameters = []): NodeInterface
    {
        $calUri = new CalUri($value);

        if (array_key_exists(Type::getNode(), $parameters)) {
            $calUri->setType($parameters[Type::getNode()]);
        }

        return $calUri;
    }
}
