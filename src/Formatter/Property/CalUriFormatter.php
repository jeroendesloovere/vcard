<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Formatter\Property;

use JeroenDesloovere\VCard\Property\CalUri;

final class CalUriFormatter extends NodeFormatter implements NodeFormatterInterface
{
    /** @var CalUri */
    protected $node;

    public function __construct(CalUri $node)
    {
        $this->node = $node;
    }

    public function getVcfString(): string
    {
        return $this->node::getNode() . ';TYPE=' . $this->node->getType()->__toString() . ':' . $this->node->getUri();
    }
}
