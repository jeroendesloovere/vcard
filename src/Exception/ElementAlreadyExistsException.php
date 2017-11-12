<?php

namespace JeroenDesloovere\VCard\Exception;

/**
 * Class ElementAlreadyExistsException
 *
 * @package JeroenDesloovere\VCard\Exception
 */
class ElementAlreadyExistsException extends VCardException
{
    /**
     * ElementAlreadyExistsException constructor.
     *
     * @param string $element
     */
    public function __construct(string $element)
    {
        parent::__construct('You can only set "'.$element.'" once.');
    }
}
