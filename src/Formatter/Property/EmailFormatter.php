<?php

declare(strict_types=1);

namespace Dilone\VCard\Formatter\Property;

use Dilone\VCard\Property\Email;

final class EmailFormatter extends NodeFormatter implements NodeFormatterInterface
{
    /** @var Email */
    protected $email;

    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    public function getVcfString(): string
    {
        return $this->email::getNode() . ';TYPE=' . $this->email->getType()->__toString() . ':' . $this->email->getEmail();
    }
}
