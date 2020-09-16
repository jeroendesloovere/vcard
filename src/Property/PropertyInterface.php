<?php

declare(strict_types=1);

namespace Dilone\VCard\Property;

interface PropertyInterface
{
    public function isAllowedMultipleTimes(): bool;
}
