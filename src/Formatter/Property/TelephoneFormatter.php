<?php

declare(strict_types=1);

namespace Dilone\VCard\Formatter\Property;

use Dilone\VCard\Property\Telephone;

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
            ';VALUE=' . $this->telephone->getValue() . ':tel:' . $this->telephone->getTelephoneNumber();
    }
}
