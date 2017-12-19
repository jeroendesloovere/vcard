<?php

namespace JeroenDesloovere\VCard\Formatter\Property;

use JeroenDesloovere\VCard\Property\FullName;

final class FullNameFormatter extends NodeFormatter implements NodeFormatterInterface
{
    /**
     * @var FullName
     */
    protected $fullName;

    public function __construct(FullName $fullName)
    {
        $this->fullName = $fullName;
    }

    public function getVcfString(): string
    {
        return $this->fullName->getNode() . ':' . $this->escape($this->fullName->getValue());
    }
}
