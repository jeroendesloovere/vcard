<?php

namespace JeroenDesloovere\VCard\Property\Value;

use JeroenDesloovere\VCard\Exception\PropertyException;

class ImageValue
{
    /** @var string - The raw image value or the external URL */
    private $value;

    private const BASE_64_DECODED = 'base_64_decoded';
    private const BASE_64_ENCODED = 'base_64_encoded';
    private const LOCAL_IMAGE_PATH = 'local_image_path';
    private const URL = 'url';

    public function __construct(string $value)
    {
        $this->setValue($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    private function getValueType(string $value): string
    {
        if ($this->isBase64Encoded($value)) {
            return self::BASE_64_ENCODED;
        }

        if ($this->isURL($value)) {
            return self::URL;
        }

        if ($this->isLocalImagePath($value)) {
            return self::LOCAL_IMAGE_PATH;
        }

        return self::BASE_64_DECODED;
    }

    private function isBase64Encoded(string $content): bool
    {
        return strpos($content, 'data') === 0;
    }

    private function isLocalImagePath(string $localImagePath): bool
    {
        try {
            if (is_file($localImagePath)) {
                return true;
            }
        } catch (\Exception $e) {
            return false;
        }

        return false;
    }

    private function isURL(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_URL) !== false;
    }

    private function isValidLocalImage(string $imagePath): bool
    {
        $mimeType = mime_content_type($imagePath);
        $this->sanitizeMimeType($mimeType);

        return $this->isValidMimeType($mimeType);
    }

    private function isValidImageURL(string $URL): bool
    {
        $headers = get_headers($URL, 1);

        if (array_key_exists('Content-Type', $headers)) {
            $mimeType = $headers['Content-Type'];

            if (is_array($mimeType)) {
                $mimeType = end($mimeType);
            }
        }

        $this->sanitizeMimeType($mimeType);

        return $this->isValidMimeType($mimeType);
    }

    private function isValidImageContent(string $value): bool
    {
        $finfo = new \finfo();
        $mimeType = $finfo->buffer($value, FILEINFO_MIME_TYPE);
        $this->sanitizeMimeType($mimeType);

        return $this->isValidMimeType($mimeType);
    }

    private function isValidMimeType(string $mimeType): bool
    {
        if (!is_string($mimeType) || substr($mimeType, 0, 6) !== 'image/') {
            return false;
        }
        ;
        return true;
    }

    private function sanitizeMimeType(string &$mimeType)
    {
        if (strpos($mimeType, ';') !== false) {
            $mimeType = strstr($mimeType, ';', true);
        }
    }

    private function setValue(string $value): void
    {
        $valueType = $this->getValueType($value);

        switch ($valueType) {
            case self::LOCAL_IMAGE_PATH:
                if (!$this->isValidLocalImage($value)) {
                    throw PropertyException::forInvalidImage();
                }

                try {
                    $value = file_get_contents(realpath($value));
                } catch (\Exception $e) {
                    throw PropertyException::forInvalidImage();
                }

                break;
            case self::BASE_64_DECODED:
                if (!$this->isValidImageContent($value)) {
                    throw PropertyException::forInvalidImage();
                }

                $value = base64_encode($value);

                break;
            case self::URL:
                if (!$this->isValidImageURL($value)) {
                    throw PropertyException::forInvalidImage();
                }

                break;
            default:
                break;
        }

        $this->value = $value;
    }
}
