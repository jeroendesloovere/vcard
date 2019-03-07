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
        // According to the Timestamp rfc6350 standard in Zulu zone (UTC)
        return $this->value->format('Ymd\THis\Z');
    }

    public function getValue(): \DateTime
    {
        return $this->value;
    }
}
