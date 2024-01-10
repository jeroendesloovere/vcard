<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Property;

use Brick\PhoneNumber\PhoneNumber;
use Brick\PhoneNumber\PhoneNumberFormat;
use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Formatter\Property\TelephoneFormatter;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Parser\Property\TelephoneParser;
use JeroenDesloovere\VCard\Property\Parameter\Type;
use JeroenDesloovere\VCard\Property\Parameter\Value;

final class Telephone implements PropertyInterface, NodeInterface
{
    /** @var string */
    protected $telephoneNumber;

    /** @var PhoneNumber */
    protected $phoneNumber;

    /** @var Type */
    private $type;

    /** @var Value */
    private $value;

    public function __construct(string $telephoneNumber, Type $type = null, Value $value = null)
    {

        $this->phoneNumber = PhoneNumber::parse($telephoneNumber);
        $this->telephoneNumber = $this->phoneNumber->format(PhoneNumberFormat::NATIONAL);

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

    public function getTelephoneNumberURI(): string
    {
        return $this->phoneNumber->format(PhoneNumberFormat::RFC3966);
    }

    public function getPhoneNumber(): PhoneNumber
    {
        return $this->phoneNumber;
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
