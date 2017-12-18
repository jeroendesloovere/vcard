<?php

namespace JeroenDesloovere\VCard\Formatter\Property;

interface NodeFormatterInterface
{
    public function getVcfString(): string;
}
