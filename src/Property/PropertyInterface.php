<?php

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Formatter\Property\PropertyFormatterInterface;

interface PropertyInterface extends NodeInterface
{
    public function getFormatter(): PropertyFormatterInterface;
    public function isAllowedMultipleTimes(): bool;
}
