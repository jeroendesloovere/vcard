<?php

namespace JeroenDesloovere\VCard\Exception;

/**
 * Class InvalidVersionException
 *
 * @package JeroenDesloovere\VCard\Exception
 */
class InvalidVersionException extends VCardException
{
    /**
     * InvalidVersionException constructor.
     */
    public function __construct()
    {
        parent::__construct('Invalid VCard version.');
    }
}
