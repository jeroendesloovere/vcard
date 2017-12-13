<?php

namespace JeroenDesloovere\VCard\Formatter\Property;

use JeroenDesloovere\VCard\Property\Name;

class NameFormatter extends PropertyFormatter implements PropertyFormatterInterface
{
    /**
     * @var Name
     */
    protected $name;

    public function __construct(Name $name)
    {
        $this->name = $name;
    }

    public function getVcfString(): string
    {
        return $this->name->getNode() . ':' . $this->escape(
            $this->name->getFirstName() . ';' . $this->name->getAdditional() . ';' . $this->name->getLastName() . ';' . $this->name->getPrefix() . ';' . $this->name->getSuffix()
        );
    }
}
