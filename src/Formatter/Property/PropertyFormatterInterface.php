<?php

namespace JeroenDesloovere\VCard\Formatter\Property;

use JeroenDesloovere\VCard\Property\PropertyInterface;

interface PropertyFormatterInterface
{
    public function convertToVcfString(PropertyInterface $property): string;
}
