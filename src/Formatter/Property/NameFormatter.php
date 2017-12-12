<?php

namespace JeroenDesloovere\VCard\Formatter\Property;

use JeroenDesloovere\VCard\Property\Name;
use JeroenDesloovere\VCard\Property\PropertyInterface;

class NameFormatter extends PropertyFormatter implements PropertyFormatterInterface
{
    /**
     * @param Name|PropertyInterface $name
     * @return string
     */
    public function convertToVcfString(PropertyInterface $name): string
    {
        return $name->getNode().':'.$this->escape(
            $name->getFirstName().';'.$name->getAdditional().';'.$name->getLastName().';'.$name->getPrefix().';'.$name->getSuffix()
        );
    }
}