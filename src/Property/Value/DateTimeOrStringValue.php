<?php

declare(strict_types=1);

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
            // According to DATE-AND-OR-TIME rfc6350 standard
            return $this->value->format('Ymd\THis');
        }

        return $this->value;
    }

    public function getValue()
    {
        return $this->value;
    }
}
