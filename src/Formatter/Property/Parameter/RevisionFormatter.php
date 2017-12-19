<?php

namespace JeroenDesloovere\VCard\Formatter\Property\Parameter;

use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Formatter\Property\NodeFormatter;
use JeroenDesloovere\VCard\Property\Parameter\Revision;

final class RevisionFormatter extends NodeFormatter implements NodeFormatterInterface
{
    /**
     * @var Revision
     */
    protected $revision;

    public function __construct(Revision $revision)
    {
        $this->revision = $revision;
    }

    public function getVcfString(): string
    {
        return $this->revision->getNode() . ':' . $this->revision->getValue();
    }
}
