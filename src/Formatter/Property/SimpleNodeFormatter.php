<?php

declare(strict_types=1);

namespace Dilone\VCard\Formatter\Property;

use Dilone\VCard\Property\SimpleNodeInterface;

class SimpleNodeFormatter extends NodeFormatter implements NodeFormatterInterface
{
    /** @var SimpleNodeInterface */
    protected $node;

    public function __construct(SimpleNodeInterface $node)
    {
        $this->node = $node;
    }

    public function getVcfString(): string
    {
        return $this->node->getNode() . ':' . $this->escape($this->node->__toString());
    }
}
