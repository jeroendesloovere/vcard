<?php

declare(strict_types=1);

namespace Dilone\VCard\Formatter\Property;

use Dilone\VCard\Property\Name;

final class NameFormatter extends NodeFormatter implements NodeFormatterInterface
{
    /** @var Name */
    protected $name;

    public function __construct(Name $name)
    {
        $this->name = $name;
    }

    public function getVcfString(): string
    {
        return $this->name::getNode() . ':' . $this->escape(implode(';', array(
            $this->name->getLastName(),
            $this->name->getFirstName(),
            $this->name->getAdditional(),
            $this->name->getPrefix(),
            $this->name->getSuffix()
        )));
    }
}
