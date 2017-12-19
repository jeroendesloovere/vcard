<?php

namespace JeroenDesloovere\VCard\Formatter\Property\Parameter;

use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Formatter\Property\NodeFormatter;
use JeroenDesloovere\VCard\Property\Parameter\Kind;

final class KindFormatter extends NodeFormatter implements NodeFormatterInterface
{
    /**
     * @var Kind
     */
    protected $kind;

    public function __construct(Kind $kind)
    {
        $this->kind = $kind;
    }

    public function getVcfString(): string
    {
        return $this->kind->getNode() . ':' . $this->kind->getValue();
    }
}
