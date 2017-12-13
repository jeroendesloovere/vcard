<?php

namespace JeroenDesloovere\VCard\Formatter\Property;

interface PropertyFormatterInterface
{
    public function getVcfString(): string;
}
