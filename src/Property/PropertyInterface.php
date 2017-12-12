<?php

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Formatter\Property\PropertyFormatterInterface;

interface PropertyInterface
{
    public function getFormatter(): PropertyFormatterInterface;
}
