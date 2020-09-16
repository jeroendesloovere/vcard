<?php

declare(strict_types=1);

namespace Dilone\VCard\Formatter\Property;

interface NodeFormatterInterface
{
    public function getVcfString(): string;
}
