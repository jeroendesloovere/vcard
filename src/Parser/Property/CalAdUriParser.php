<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Parser\Property;

use JeroenDesloovere\VCard\Property\CalAdUri;
use JeroenDesloovere\VCard\Property\NodeInterface;
use JeroenDesloovere\VCard\Property\Parameter\Type;

final class CalAdUriParser extends PropertyParser implements NodeParserInterface
{
    public function parseVcfString(string $value, array $parameters = []): NodeInterface
    {
        $calAdUri = new CalAdUri($value);

        if (array_key_exists(Type::getNode(), $parameters)) {
            $calAdUri->setType($parameters[Type::getNode()]);
        }

        return $calAdUri;
    }
}
