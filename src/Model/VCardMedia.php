<?php

namespace JeroenDesloovere\VCard\Model;

use JeroenDesloovere\VCard\Exception\EmptyUrlException;
use JeroenDesloovere\VCard\Exception\InvalidImageException;
use JeroenDesloovere\VCard\Exception\InvalidUrlException;
use JeroenDesloovere\VCard\Util\GeneralUtil;

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
    protected $fileType;

    /**
     * @var string|null
     */
    protected $url;

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
    public function getFileType(): ?string
    {
        return $this->fileType;
    }

    /**
     * @param null|string $fileType
     */
    public function setFileType(?string $fileType): void
    {
        $this->fileType = $fileType;
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

    /**
     * Add a photo or logo (depending on property name)
     *
     * @param string $url     image url or filename
     * @param bool   $include Do we include the image in our vcard or not?
     *
     * @throws EmptyUrlException
     * @throws InvalidImageException
     * @throws InvalidUrlException
     */
    public function addUrlMedia(string $url, bool $include = true): void
    {
        $mimeType = GeneralUtil::getMimeType($url);
        if (!\is_string($mimeType) || 0 !== strpos($mimeType, 'image/')) {
            throw new InvalidImageException();
        }
        $fileType = strtoupper(substr($mimeType, 6));

        if ($fileType) {
            $this->setFileType($fileType);
        }

        if ($include) {
            $value = file_get_contents($url);

            if ($value === false) {
                throw new EmptyUrlException();
            }

            $this->setRaw($value);
        } else {
            $this->setUrl($url);
        }
    }

    /**
     * Add a photo or logo (depending on property name)
     *
     * @param string      $raw
     * @param null|string $fileType
     */
    public function addRawMedia(string $raw, ?string $fileType = null): void
    {
        $this->setRaw($raw);
        $this->setFileType($fileType);
    }

    /**
     * @param string $property
     *
     * @return array
     */
    public function builderUrl(string $property): array
    {
        $url = $this->getUrl();

        $fileType = $this->getFileType();

        if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
            $propertySuffix = ';VALUE=URL';
            $propertySuffix .= ';TYPE='.strtoupper($fileType);

            $property .= $propertySuffix;
            $fileValue = $url;
        } else {
            $fileValue = $url;
        }

        return [
            'key' => $property,
            'value' => $fileValue,
        ];
    }

    /**
     * @param string $property
     *
     * @return array
     */
    public function builderRaw(string $property): array
    {
        $raw = $this->getRaw();
        $fileType = $this->getFileType();

        $raw = base64_encode($raw);

        if ($fileType !== null) {
            $property .= ';ENCODING=b;TYPE='.$fileType;
        } else {
            $property .= ';ENCODING=b';
        }

        return [
            'key' => $property,
            'value' => $raw,
        ];
    }
}
