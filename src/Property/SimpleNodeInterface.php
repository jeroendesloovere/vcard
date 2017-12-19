<?php

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;

interface SimpleNodeInterface extends NodeInterface
{
    public function __toString(): string;
}
