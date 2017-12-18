<?php

namespace JeroenDesloovere\VCard\Formatter\Property;

use JeroenDesloovere\VCard\Property\Note;

class NoteFormatter extends NodeFormatter implements NodeFormatterInterface
{
    /**
     * @var Note
     */
    protected $note;

    public function __construct(Note $note)
    {
        $this->note = $note;
    }

    public function getVcfString(): string
    {
        return $this->note->getNode() . ':' . $this->escape($this->note->getValue());
    }
}
