<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Property;

interface PropertyInterface
{
    public function isAllowedMultipleTimes(): bool;
}
