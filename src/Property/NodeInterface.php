<?php

declare(strict_types=1);

namespace Dilone\VCard\Property;

use Dilone\VCard\Formatter\Property\NodeFormatterInterface;
use Dilone\VCard\Parser\Property\NodeParserInterface;

interface NodeInterface
{
    public function getFormatter(): NodeFormatterInterface;
    public static function getParser(): NodeParserInterface;
    public static function getNode(): string;
}
