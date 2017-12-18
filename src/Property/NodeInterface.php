<?php

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;

interface NodeInterface
{
    public static function getParser(): NodeParserInterface;
    public static function getNode(): string;
}
