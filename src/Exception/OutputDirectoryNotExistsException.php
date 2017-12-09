<?php

namespace JeroenDesloovere\VCard\Exception;

/**
 * Class OutputDirectoryNotExistsException
 *
 * @package JeroenDesloovere\VCard\Exception
 */
class OutputDirectoryNotExistsException extends VCardException
{
    /**
     * OutputDirectoryNotExistsException constructor.
     */
    public function __construct()
    {
        parent::__construct('Output directory does not exist.');
    }
}
