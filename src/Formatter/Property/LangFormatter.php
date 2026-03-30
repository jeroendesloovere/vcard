<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Formatter\Property;

use JeroenDesloovere\VCard\Property\Lang;

final class LangFormatter extends NodeFormatter implements NodeFormatterInterface
{
    /** @var Lang */
    protected $node;

    public function __construct(Lang $node)
    {
        $this->node = $node;
    }

    public function getVcfString(): string
    {
        return $this->node::getNode() . ';TYPE=' . $this->node->getType()->__toString() . ':' . $this->node->getLanguage();
    }
}
