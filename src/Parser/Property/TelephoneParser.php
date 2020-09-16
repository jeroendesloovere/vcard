<?php

declare(strict_types=1);

namespace Dilone\VCard\Parser\Property;

use Dilone\VCard\Property\NodeInterface;
use Dilone\VCard\Property\Telephone;
use Dilone\VCard\Property\Parameter\Type;
use Dilone\VCard\Property\Parameter\Value;

final class TelephoneParser extends PropertyParser implements NodeParserInterface
{
    public function parseVcfString(string $value, array $parameters = []): NodeInterface
    {
        $telephone = new Telephone(str_replace('tel:', '', $value));

        if (array_key_exists(Type::getNode(), $parameters)) {
            $telephone->setType($parameters[Type::getNode()]);
        }

        if (array_key_exists(Value::getNode(), $parameters)) {
            $telephone->setValue($parameters[Value::getNode()]);
        }

        return $telephone;
    }
}
