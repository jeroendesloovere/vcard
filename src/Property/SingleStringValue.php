<?php

namespace JeroenDesloovere\VCard\Property;

class SingleStringValue
{
    /**
     * @var string
     */
    protected $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
