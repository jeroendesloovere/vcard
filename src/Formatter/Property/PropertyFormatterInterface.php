<?php

namespace JeroenDesloovere\VCard\Formatter\Property;

use JeroenDesloovere\VCard\Property\PropertyInterface;

/**
 * Interface PropertyFormatterInterface
 *
 * @package JeroenDesloovere\VCard\Formatter\Property
 */
interface PropertyFormatterInterface
{
    /**
     * @param PropertyInterface $property
     *
     * @return string
     */
    public function convertToVcfString(PropertyInterface $property): string;
}
