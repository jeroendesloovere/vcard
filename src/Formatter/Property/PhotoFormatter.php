<?php

namespace JeroenDesloovere\VCard\Formatter\Property;

use JeroenDesloovere\VCard\Property\Photo;

final class PhotoFormatter extends NodeFormatter implements NodeFormatterInterface
{
    /** @var Photo */
    protected $photo;

    public function __construct(Photo $photo)
    {
        $this->photo = $photo;
    }

    public function getVcfString(): string
    {
        $string = Photo::getNode();

        if ($this->photo->isExternalUrl()) {
            $string .= ';VALUE=uri';
        }

        if ($this->photo->isInclude()) {
            $string .= ';ENCODING=b';
        }

        $string .= ':' . $this->getContent();

        return $string;
    }

    private function getContent(): string
    {
        if ($this->photo->isInclude()) {
            return base64_encode($this->photo->getContent());
        }

        return $this->photo->getContent();
    }
}
