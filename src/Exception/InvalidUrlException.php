<?php

namespace JeroenDesloovere\VCard\Exception;

/**
 * Class InvalidUrlException
 *
 * @package JeroenDesloovere\VCard\Exception
 */
class InvalidUrlException extends VCardException
{
    /**
     * InvalidUrlException constructor.
     */
    public function __construct()
    {
        parent::__construct('Invalid Url.');
    }
}
