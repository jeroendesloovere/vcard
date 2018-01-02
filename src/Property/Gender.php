<?php

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Exception\PropertyException;
use JeroenDesloovere\VCard\Formatter\Property\GenderFormatter;
use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Parser\Property\GenderParser;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Property\Parameter\GenderType;

final class Gender implements PropertyInterface, NodeInterface
{
    /**
     * @var GenderType
     */
    private $gender;

    /**
     * @var null|string
     */
    private $note;

    public function __construct(?GenderType $gender = null, ?string $note = null)
    {
        if ($gender === null && $note === null) {
            throw PropertyException::forEmptyProperty();
        }

        $this->gender = $gender ?? GenderType::empty();
        $this->note = $note;
    }

    public function getFormatter(): NodeFormatterInterface
    {
        return new GenderFormatter($this);
    }

    public static function getNode(): string
    {
        return 'GENDER';
    }

    public static function getParser(): NodeParserInterface
    {
        return new GenderParser();
    }

    public function isAllowedMultipleTimes(): bool
    {
        return false;
    }

    public function getGender(): GenderType
    {
        return $this->gender;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }
}
