<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;

interface NodeInterface
{
    public function getFormatter(): NodeFormatterInterface;
    public static function getParser(): NodeParserInterface;
    public static function getNode(): string;
}
