<?php

namespace JeroenDesloovere\VCard\Property\Parameter;

use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Parser\Property\RevisionParser;

final class Revision implements PropertyParameterInterface
{
    /**
     * @var \DateTime
     */
    private $value;

    public function __construct(\DateTime $dateTime)
    {
        $this->value = $dateTime;
    }

    public function __toString()
    {
        return $this->getValue();
    }

    public static function getNode(): string
    {
        return 'REV';
    }

    public static function getParser(): NodeParserInterface
    {
        return new RevisionParser();
    }

    public function getValue(): string
    {
        return $this->value->format('u');
    }
}
