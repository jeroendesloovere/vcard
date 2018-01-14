<?php

namespace JeroenDesloovere\VCard\Formatter\Property;

use JeroenDesloovere\VCard\Property\Email;

final class EmailFormatter extends NodeFormatter implements NodeFormatterInterface
{
    /**
     * @var Email
     */
    protected $email;

    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    public function getVcfString(): string
    {
        $string = Email::getNode();
        $string .= ';TYPE=' . $this->email->getType()->__toString();
        $string .= ':' . $this->email->getEmail();

        return $string;
    }
}
