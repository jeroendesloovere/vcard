<?php

namespace JeroenDesloovere\VCard\Property\Value;

class DateTimeOrStringValue
{
    /** @var string|\DateTime */
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        if ($this->value instanceof \DateTime) {
            return $this->value->format('u');
        }

        return $this->value;
    }

    public function getValue()
    {
        return $this->value;
    }
}
