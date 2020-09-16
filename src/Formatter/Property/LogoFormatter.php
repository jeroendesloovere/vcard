<?php

declare(strict_types=1);

namespace Dilone\VCard\Formatter\Property;

use Dilone\VCard\Property\Logo;

final class LogoFormatter extends NodeFormatter implements NodeFormatterInterface
{
    /** @var Logo */
    protected $logo;

    public function __construct(Logo $logo)
    {
        $this->logo = $logo;
    }

    public function getVcfString(): string
    {
        return $this->logo::getNode() . ':' . $this->logo->getValue();
    }
}
