<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Formatter\Property;

use JeroenDesloovere\VCard\Property\FbUrl;

final class FbUrlFormatter extends NodeFormatter implements NodeFormatterInterface
{
    /** @var FbUrl */
    protected $node;

    public function __construct(FbUrl $node)
    {
        $this->node = $node;
    }

    public function getVcfString(): string
    {
        return $this->node::getNode() . ';TYPE=' . $this->node->getType()->__toString() . ':' . $this->node->getUrl();
    }
}
