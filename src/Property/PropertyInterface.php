<?php

namespace JeroenDesloovere\VCard\Property;

interface PropertyInterface extends NodeInterface
{
    public function isAllowedMultipleTimes(): bool;
}
