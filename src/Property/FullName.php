<?php

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Formatter\Property\FullNameFormatter;
use JeroenDesloovere\VCard\Formatter\Property\PropertyFormatterInterface;

final class FullName implements PropertyInterface
{
    /**
     * @var string
     */
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function fromVcfString(string $value): self
    {
        return new self($value);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getFormatter(): PropertyFormatterInterface
    {
        return new FullNameFormatter($this);
    }

    public function getNode(): string
    {
        return 'FN';
    }

    public function isAllowedMultipleTimes(): bool
    {
        return false;
    }
}
