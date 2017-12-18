<?php

namespace JeroenDesloovere\VCard\Formatter\Property\Parameter;

use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Formatter\Property\NodeFormatter;
use JeroenDesloovere\VCard\Property\Parameter\Version;

class VersionFormatter extends NodeFormatter implements NodeFormatterInterface
{
    /**
     * @var Version
     */
    protected $version;

    public function __construct(Version $version)
    {
        $this->version = $version;
    }

    public function getVcfString(): string
    {
        return $this->version->getNode() . ':' . $this->version->getValue();
    }
}
