<?php

namespace JeroenDesloovere\VCard\Model;

/**
 * Class VCardMedia
 *
 * @package JeroenDesloovere\VCard\Model
 */
class VCardMedia
{
    /**
     * @var string|null
     */
    protected $raw;

    /**
     * @var string|null
     */
    protected $url;

//    /**
//     * @return null|string
//     */
//    public function getMedia(): ?string
//    {
//        return null;
//    }
//
//    /**
//     * @param null|string $media
//     */
//    public function setMedia(?string $media): void
//    {
//        // dd
//    }

    /**
     * @return null|string
     */
    public function getRaw(): ?string
    {
        return $this->raw;
    }

    /**
     * @param null|string $raw
     */
    public function setRaw(?string $raw): void
    {
        $this->raw = $raw;
    }

    /**
     * @return null|string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param null|string $url
     */
    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    /**
     * @param null|string $value
     * @param bool        $isRawValue
     */
    public function parser(?string $value, bool $isRawValue): void
    {
        if ($isRawValue) {
            $this->setRaw($value);
        } else {
            $this->setUrl($value);
        }
    }
}
