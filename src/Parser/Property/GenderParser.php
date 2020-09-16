<?php

declare(strict_types=1);

namespace Dilone\VCard\Parser\Property;

use Dilone\VCard\Property\Gender;
use Dilone\VCard\Property\NodeInterface;

final class GenderParser extends PropertyParser implements NodeParserInterface
{
    public function parseVcfString(string $value, array $parameters = []): NodeInterface
    {
        @list($gender, $note) = explode(';', $value, 2);

        $this->convertEmptyStringToNull([$note]);

        return new Gender($gender, $note);
    }
}
