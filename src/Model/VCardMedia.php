<?php

namespace JeroenDesloovere\VCard\Model;

use JeroenDesloovere\VCard\Exception\EmptyUrlException;
use JeroenDesloovere\VCard\Exception\InvalidImageException;

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

//    /**
//     * Add a photo or logo (depending on property name)
//     *
//     * @param string $property LOGO|PHOTO
//     * @param string $url      image url or filename
//     * @param string $element  The name of the element to set
//     * @param bool   $include  Do we include the image in our vcard or not?
//     *
//     * @throws ElementAlreadyExistsException
//     * @throws EmptyUrlException
//     * @throws InvalidImageException
//     */
//    protected function addMedia(string $property, string $url, string $element, bool $include = true): void
//    {
//        $mimeType = null;
//
//        //Is this URL for a remote resource?
//        if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
//            $headers = get_headers($url, 1);
//
//            if (array_key_exists('Content-Type', $headers)) {
//                $mimeType = $headers['Content-Type'];
//            }
//        } else {
//            //Local file, so inspect it directly
//            $mimeType = mime_content_type($url);
//        }
//        if (strpos($mimeType, ';') !== false) {
//            $mimeType = strstr($mimeType, ';', true);
//        }
//        if (!\is_string($mimeType) || 0 !== strpos($mimeType, 'image/')) {
//            throw new InvalidImageException();
//        }
//        $fileType = strtoupper(substr($mimeType, 6));
//
//        if ($include) {
//            $value = file_get_contents($url);
//
//            if (!$value) {
//                throw new EmptyUrlException();
//            }
//
//            $value = base64_encode($value);
//            $property .= ';ENCODING=b;TYPE='.$fileType;
//        } else {
//            if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
//                $propertySuffix = ';VALUE=URL';
//                $propertySuffix .= ';TYPE='.strtoupper($fileType);
//
//                $property .= $propertySuffix;
//                $value = $url;
//            } else {
//                $value = $url;
//            }
//        }
//
//        $this->setProperty(
//            $element,
//            $property,
//            $value
//        );
//    }
//
//    /**
//     * Add a photo or logo (depending on property name)
//     *
//     * @param string      $property LOGO|PHOTO
//     * @param string      $raw      image url or filename
//     * @param string      $element  The name of the element to set
//     * @param null|string $fileType
//     *
//     * @throws ElementAlreadyExistsException
//     */
//    protected function addRawMedia(string $property, string $raw, string $element, ?string $fileType = null): void
//    {
//        $raw = base64_encode($raw);
//
//        if ($fileType !== null) {
//            $property .= ';ENCODING=b;TYPE='.$fileType;
//        } else {
//            $property .= ';ENCODING=b';
//        }
//
//        $this->setProperty(
//            $element,
//            $property,
//            $raw
//        );
//    }

    /**
     * @param string $property
     * @param bool   $include
     *
     * @return array
     * @throws EmptyUrlException
     * @throws InvalidImageException
     */
    public function builderUrl(string $property, bool $include = true): array
    {
        $url = $this->getUrl();

        $mimeType = null;

        //Is this URL for a remote resource?
        if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
            $headers = get_headers($url, 1);

            if (array_key_exists('Content-Type', $headers)) {
                $mimeType = $headers['Content-Type'];
            }
        } else {
            //Local file, so inspect it directly
            $mimeType = mime_content_type($url);
        }
        if (strpos($mimeType, ';') !== false) {
            $mimeType = strstr($mimeType, ';', true);
        }
        if (!\is_string($mimeType) || 0 !== strpos($mimeType, 'image/')) {
            throw new InvalidImageException();
        }
        $fileType = strtoupper(substr($mimeType, 6));

        if ($include) {
            $fileValue = file_get_contents($url);

            if (!$fileValue) {
                throw new EmptyUrlException();
            }

            $fileValue = base64_encode($fileValue);
            $property .= ';ENCODING=b;TYPE='.$fileType;
        } else {
            if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
                $propertySuffix = ';VALUE=URL';
                $propertySuffix .= ';TYPE='.strtoupper($fileType);

                $property .= $propertySuffix;
                $fileValue = $url;
            } else {
                $fileValue = $url;
            }
        }

        return [
            'key' => $property,
            'value' => $fileValue,
        ];
    }

    /**
     * @param string      $property
     * @param null|string $fileType
     *
     * @return array
     */
    public function builderRaw(string $property, ?string $fileType = null): array
    {
        $raw = $this->getRaw();

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
