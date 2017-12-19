<?php

namespace JeroenDesloovere\VCard\Formatter\Property\Parameter;

use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Formatter\Property\NodeFormatter;
use JeroenDesloovere\VCard\Property\Parameter\Type;

final class TypeFormatter extends NodeFormatter implements NodeFormatterInterface
{
    /**
     * @var Type
     */
    protected $type;

    public function __construct(Type $type)
    {
        $this->type = $type;
    }

    public function getVcfString(): string
    {
        return $this->type->getNode() . ':' . $this->type->getValue();
    }
}
