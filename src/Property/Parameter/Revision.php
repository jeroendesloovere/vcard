<?php

declare(strict_types=1);

namespace Dilone\VCard\Property\Parameter;

use Dilone\VCard\Formatter\Property\NodeFormatterInterface;
use Dilone\VCard\Formatter\Property\Parameter\RevisionFormatter;
use Dilone\VCard\Parser\Property\NodeParserInterface;
use Dilone\VCard\Parser\Property\RevisionParser;
use Dilone\VCard\Property\SimpleNodeInterface;
use Dilone\VCard\Property\Value\DateTimeValue;

final class Revision extends DateTimeValue implements PropertyParameterInterface, SimpleNodeInterface
{
    public function getFormatter(): NodeFormatterInterface
    {
        return new RevisionFormatter($this);
    }

    public static function getNode(): string
    {
        return 'REV';
    }

    public static function getParser(): NodeParserInterface
    {
        return new RevisionParser();
    }
}
