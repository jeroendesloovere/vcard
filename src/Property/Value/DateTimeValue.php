<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Property\Value;

class DateTimeValue
{
    /** @var \DateTime */
    protected $value;

    public function __construct(\DateTime $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        // TODO: possibly not in line with rfc6350
        return $this->value->format('u');
    }

    public function getValue(): \DateTime
    {
        return $this->value;
    }
}
