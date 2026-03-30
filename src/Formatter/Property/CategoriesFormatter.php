<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Formatter\Property;

use JeroenDesloovere\VCard\Property\Categories;

final class CategoriesFormatter extends NodeFormatter implements NodeFormatterInterface
{
    /** @var Categories */
    private $node;

    public function __construct(Categories $node)
    {
        $this->node = $node;
    }

    public function getVcfString(): string
    {
        return $this->node->getNode() . ':' . $this->escape((string) $this->node);
    }
}
