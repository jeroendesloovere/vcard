<?php

namespace JeroenDesloovere\VCard\Exception;

/**
 * Class InvalidImageException
 *
 * @package JeroenDesloovere\VCard\Exception
 */
class InvalidImageException extends VCardException
{
    /**
     * InvalidImageException constructor.
     */
    public function __construct()
    {
        parent::__construct('Returned data is not an image.');
    }
}
