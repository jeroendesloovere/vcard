<?php

namespace JeroenDesloovere\VCard\Formatter\Property;

use JeroenDesloovere\VCard\Property\Gender;

final class GenderFormatter extends NodeFormatter implements NodeFormatterInterface
{
    /**
     * @var Gender
     */
    protected $gender;

    public function __construct(Gender $gender)
    {
        $this->gender = $gender;
    }

    public function getVcfString(): string
    {
        $string = Gender::getNode();
        $string .= ':' . $this->gender->getGender()->__toString();

        if ($this->gender->getNote() === null) {
            $string .= ';' . $this->escape($this->gender->getNote());
        }

        return $string;
    }
}
