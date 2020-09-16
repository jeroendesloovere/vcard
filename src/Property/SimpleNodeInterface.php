<?php

declare(strict_types=1);

namespace Dilone\VCard\Property;

interface SimpleNodeInterface extends NodeInterface
{
    public function __toString(): string;
}
