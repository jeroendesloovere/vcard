<?php

namespace JeroenDesloovere\VCard\Property\Parameter;

use JeroenDesloovere\VCard\Property\NodeInterface;

interface PropertyParameterInterface extends NodeInterface
{
    public function getValue(): string;
}
