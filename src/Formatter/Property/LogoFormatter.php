<?php

namespace JeroenDesloovere\VCard\Formatter\Property;

use JeroenDesloovere\VCard\Property\Logo;

final class LogoFormatter extends NodeFormatter implements NodeFormatterInterface
{
    /** @var Logo */
    protected $logo;

    public function __construct(Logo $logo)
    {
        $this->logo = $logo;
    }

    public function getVcfString(): string
    {
        return $this->logo::getNode() . ':' . $this->logo->getValue();
    }
}
