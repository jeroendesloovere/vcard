<?php

namespace JeroenDesloovere\VCard\Property\Parameter;

use JeroenDesloovere\VCard\Property\NodeInterface;

/**
 * Interface PropertyParameterInterface
 *
 * @package JeroenDesloovere\VCard\Property\Parameter
 */
interface PropertyParameterInterface extends NodeInterface
{
    /**
     * @return string
     */
    public function getValue(): string;
}
