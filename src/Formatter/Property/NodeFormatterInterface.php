<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Formatter\Property;

interface NodeFormatterInterface
{
    public function getVcfString(): string;
}
