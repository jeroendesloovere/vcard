<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Formatter\Property;

use JeroenDesloovere\VCard\Property\Org;

final class OrgFormatter extends NodeFormatter implements NodeFormatterInterface
{
    /** @var Org */
    protected $node;

    public function __construct(Org $node)
    {
        $this->node = $node;
    }

    public function getVcfString(): string
    {
        $parts = array_merge([$this->node->getOrganizationName()], $this->node->getUnits());
        return $this->node::getNode() . ':' . implode(';', $parts);
    }
}
