<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Parser\Property;

use JeroenDesloovere\VCard\Property\Lang;
use JeroenDesloovere\VCard\Property\NodeInterface;
use JeroenDesloovere\VCard\Property\Parameter\Type;

final class LangParser extends PropertyParser implements NodeParserInterface
{
    public function parseVcfString(string $value, array $parameters = []): NodeInterface
    {
        $lang = new Lang($value);

        if (array_key_exists(Type::getNode(), $parameters)) {
            $lang->setType($parameters[Type::getNode()]);
        }

        return $lang;
    }
}
