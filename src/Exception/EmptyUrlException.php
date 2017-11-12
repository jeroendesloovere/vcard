<?php

namespace JeroenDesloovere\VCard\Exception;

/**
 * Class EmptyUrlException
 *
 * @package JeroenDesloovere\VCard\Exception
 */
class EmptyUrlException extends VCardException
{
    /**
     * EmptyUrlException constructor.
     */
    public function __construct()
    {
        parent::__construct('Nothing returned from URL.');
    }
}
