<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Property\Parameter;

use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Formatter\Property\Parameter\RevisionFormatter;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Parser\Property\RevisionParser;
use JeroenDesloovere\VCard\Property\SimpleNodeInterface;
use JeroenDesloovere\VCard\Property\Value\DateTimeValue;

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
