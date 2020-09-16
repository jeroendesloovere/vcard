<?php

declare(strict_types=1);

namespace Dilone\VCard\Property;

use Dilone\VCard\Formatter\Property\NodeFormatterInterface;
use Dilone\VCard\Formatter\Property\TelephoneFormatter;
use Dilone\VCard\Parser\Property\NodeParserInterface;
use Dilone\VCard\Parser\Property\TelephoneParser;
use Dilone\VCard\Property\Parameter\Type;
use Dilone\VCard\Property\Parameter\Value;

final class Telephone implements PropertyInterface, NodeInterface
{
    /** @var string */
    protected $telephoneNumber;

    /** @var Type */
    private $type;

    /** @var Value */
    private $value;

    public function __construct(string $telephoneNumber, Type $type = null, Value $value = null)
    {
        $this->telephoneNumber = str_replace(' ', '-', $telephoneNumber);
        $this->type = $type ?? Type::home();
        $this->value = $value ?? Value::uri();
    }

    public function __toString(): string
    {
        return $this->telephoneNumber;
    }

    public function getFormatter(): NodeFormatterInterface
    {
        return new TelephoneFormatter($this);
    }

    public static function getNode(): string
    {
        return 'TEL';
    }

    public function getTelephoneNumber(): string
    {
        return $this->telephoneNumber;
    }

    public static function getParser(): NodeParserInterface
    {
        return new TelephoneParser();
    }

    public function isAllowedMultipleTimes(): bool
    {
        return true;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function setType(Type $type)
    {
        $this->type = $type;
    }

    public function getValue(): Value
    {
        return $this->value;
    }

    public function setValue(Value $value)
    {
        $this->value = $value;
    }
}
