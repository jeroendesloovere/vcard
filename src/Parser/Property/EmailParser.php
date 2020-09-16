<?php

declare(strict_types=1);

namespace Dilone\VCard\Parser\Property;

use Dilone\VCard\Property\Email;
use Dilone\VCard\Property\NodeInterface;
use Dilone\VCard\Property\Parameter\Type;

final class EmailParser extends PropertyParser implements NodeParserInterface
{
    public function parseVcfString(string $value, array $parameters = []): NodeInterface
    {
        $email = new Email($value);

        if (array_key_exists(Type::getNode(), $parameters)) {
            $email->setType($parameters[Type::getNode()]);
        }

        return $email;
    }
}
