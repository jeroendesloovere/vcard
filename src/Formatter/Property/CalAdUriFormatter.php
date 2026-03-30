<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Formatter\Property;

use JeroenDesloovere\VCard\Property\CalAdUri;

final class CalAdUriFormatter extends NodeFormatter implements NodeFormatterInterface
{
    /** @var CalAdUri */
    protected $node;

    public function __construct(CalAdUri $node)
    {
        $this->node = $node;
    }

    public function getVcfString(): string
    {
        return $this->node::getNode() . ';TYPE=' . $this->node->getType()->__toString() . ':' . $this->node->getUri();
    }
}
