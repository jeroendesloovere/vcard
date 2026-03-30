<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Formatter\Property;

use JeroenDesloovere\VCard\Property\Related;

final class RelatedFormatter extends NodeFormatter implements NodeFormatterInterface
{
    /** @var Related */
    protected $node;

    public function __construct(Related $node)
    {
        $this->node = $node;
    }

    public function getVcfString(): string
    {
        return $this->node::getNode() . ';TYPE=' . $this->node->getType()->__toString() . ':' . $this->node->getUri();
    }
}
