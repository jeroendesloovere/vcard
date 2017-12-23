<?php

namespace JeroenDesloovere\VCard\Property;

interface SimpleNodeInterface extends NodeInterface
{
    public function __toString(): string;
}
