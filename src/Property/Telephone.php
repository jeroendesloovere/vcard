<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Formatter\Property\TelephoneFormatter;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Parser\Property\TelephoneParser;
use JeroenDesloovere\VCard\Property\Parameter\Type;

final class Telephone implements PropertyInterface, NodeInterface
{
    /** @var string */
    protected $telephone_number;

    /** @var Type */
    private $type;

    public function __construct(string $telephone_number, Type $type = null)
    {
        $this->telephone_number = str_replace(' ', '-', $telephone_number);        
        $this->type = $type ?? Type::home();
    }

    public function __toString(): string
    {
        return $this->telephone_number;
    }

    public function getFormatter(): NodeFormatterInterface
    {
        return new TelephoneFormatter($this);
    }

    public static function getNode(): string
    {
        return 'TEL';
    }

    public function gettelephone_number(): string
    {
        return $this->telephone_number;
    }

    public static function getParser(): NodeParserInterface
    {
        return new TelephoneParser();
    }

    public function isAllowedMultipleTimes(): bool
    {
        return false; // TODO: true I think?
    }
    
    public function getType(): Type
    {
        return $this->type;
    }

    public function setType(Type $type)
    {
        $this->type = $type;
    }
}
