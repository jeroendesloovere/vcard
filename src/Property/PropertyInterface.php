<?php

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Formatter\Property\PropertyFormatterInterface;

/**
 * Interface PropertyInterface
 *
 * @package JeroenDesloovere\VCard\Property
 */
interface PropertyInterface extends NodeInterface
{
    /**
     * @return PropertyFormatterInterface
     */
    public function getFormatter(): PropertyFormatterInterface;
}
