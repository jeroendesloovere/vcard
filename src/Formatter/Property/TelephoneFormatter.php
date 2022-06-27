<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Formatter\Property;

use JeroenDesloovere\VCard\Property\Telephone;

final class TelephoneFormatter implements NodeFormatterInterface
{
    /** @var Telephone */
    protected $telephone;

    public function __construct(Telephone $telephone)
    {
        $this->telephone = $telephone;
    }

    public function getVcfString(): string
    {
        return $this->telephone->getNode() .
          ';TYPE=' . $this->telephone->getType()->__toString() .
          ';VALUE=' . $this->telephone->getValue() . ';TEL:' . $this->telephone->getTelephoneNumber();
    }
}
