<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Formatter\Property;

use JeroenDesloovere\VCard\Property\Impp;

final class ImppFormatter extends NodeFormatter implements NodeFormatterInterface
{
    /** @var Impp */
    protected $node;

    public function __construct(Impp $node)
    {
        $this->node = $node;
    }

    public function getVcfString(): string
    {
        return $this->node::getNode() . ';TYPE=' . $this->node->getType()->__toString() . ':' . $this->node->getUri();
    }
}
