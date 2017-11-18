<?php

namespace JeroenDesloovere\VCard;

/*
 * This file is part of the VCard PHP Class from Jeroen Desloovere.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Iterator;
use JeroenDesloovere\VCard\Exception\InvalidVersionException;
use JeroenDesloovere\VCard\Model\VCard;
use JeroenDesloovere\VCard\Model\VCardAddress;

/**
 * VCard PHP Class to parse .vcard files.
 *
 * This class is heavily based on the Zendvcard project (seemingly abandoned),
 * which is licensed under the Apache 2.0 license.
 * More information can be found at https://code.google.com/archive/p/zendvcard/
 */
class VCardParser implements Iterator
{
    /**
     * The raw VCard content.
    *
     * @var string
     */
    protected $content;

    /**
     * The VCard data objects.
     *
     * @var array
     */
    protected $vcardObjects;

    /**
     * The iterator position.
     *
     * @var int
     */
    protected $position;

    /**
     * Helper function to parse a file directly.
     *
     * @param string $filename
     *
     * @return self
     * @throws \RuntimeException
     */
    public static function parseFromFile($filename): ?VCardParser
    {
        if (file_exists($filename) && is_readable($filename)) {
            return new self(file_get_contents($filename));
        }

        throw new \RuntimeException(sprintf("File %s is not readable, or doesn't exist.", $filename));
    }

    /**
     * VCardParser constructor.
     *
     * @param string $content
     *
     * @throws InvalidVersionException
     */
    public function __construct($content)
    {
        $this->content = $content;
        $this->vcardObjects = [];
        $this->rewind();
        $this->parse();
    }

    /**
     *
     */
    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * @return VCard|mixed|null
     */
    public function current()
    {
        if ($this->valid()) {
            return $this->getCardAtIndex($this->position);
        }

        return null;
    }

    /**
     * @return int|mixed
     */
    public function key()
    {
        return $this->position;
    }

    /**
     *
     */
    public function next(): void
    {
        $this->position++;
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return !empty($this->vcardObjects[$this->position]);
    }

    /**
     * Fetch all the imported VCards.
     *
     * @return VCard[]
     *    A list of VCard card data objects.
     */
    public function getCards(): array
    {
        return $this->vcardObjects;
    }

    /**
     * Fetch the imported VCard at the specified index.
     *
     * @throws \OutOfBoundsException
     *
     * @param int $i
     *
     * @return VCard
     *    The card data object.
     */
    public function getCardAtIndex($i)
    {
        if (isset($this->vcardObjects[$i])) {
            return $this->vcardObjects[$i];
        }
        throw new \OutOfBoundsException();
    }

    /**
     * Start the parsing process.
     *
     * This method will populate the data object.
     *
     * @throws InvalidVersionException
     */
    protected function parse()
    {
        // Normalize new lines.
        $this->content = str_replace(["\r\n", "\r"], "\n", $this->content);

        // RFC2425 5.8.1. Line delimiting and folding
        // Unfolding is accomplished by regarding CRLF immediately followed by
        // a white space character (namely HTAB ASCII decimal 9 or. SPACE ASCII
        // decimal 32) as equivalent to no characters at all (i.e., the CRLF
        // and single white space character are removed).
        $this->content = preg_replace("/\n(?:[ \t])/", '', $this->content);
        $lines = explode("\n", $this->content);

        // Parse the VCard, line by line.
        foreach ($lines as $line) {
            $line = trim($line);

            if (strtoupper($line) === 'BEGIN:VCARD') {
                $cardData = new VCard();
            } elseif (strtoupper($line) === 'END:VCARD') {
                $this->vcardObjects[] = $cardData;
            } elseif (!empty($line)) {
                // Strip grouping information. We don't use the group names. We
                // simply use a list for entries that have multiple values.
                // As per RFC, group names are alphanumerical, and end with a
                // period (.).
                $line = preg_replace('/^\w+\./', '', $line);

                @list($type, $value) = explode(':', $line, 2);

                $types = explode(';', $type);
                $element = strtoupper($types[0]);

                array_shift($types);

                // Normalize types. A type can either be a type-param directly,
                // or can be prefixed with "type=". E.g.: "INTERNET" or
                // "type=INTERNET".
                if (!empty($types)) {
                    $types = array_map(function ($type) {
                        return preg_replace('/^type=/i', '', $type);
                    }, $types);
                }

                $rawValue = false;
                foreach ($types as $i => $type) {
                    if (false !== stripos($type, 'base64')) {
                        $value = base64_decode($value);
                        unset($types[$i]);
                        $rawValue = true;
                    } elseif (preg_match('/encoding=b/', strtolower($type))) {
                        $value = base64_decode($value);
                        unset($types[$i]);
                        $rawValue = true;
                    } elseif (false !== stripos($type, 'quoted-printable')) {
                        $value = quoted_printable_decode($value);
                        unset($types[$i]);
                        $rawValue = true;
                    } elseif (stripos($type, 'charset=') === 0) {
                        try {
                            $value = mb_convert_encoding($value, 'UTF-8', substr($type, 8));
                        } catch (\Exception $e) {
                            // not empty
                        }
                        unset($types[$i]);
                    }
                }

                switch (strtoupper($element)) {
                    case 'FN':
                        $cardData->setFullName($value);
                        break;
                    case 'N':
                        @list(
                            $lastname,
                            $firstname,
                            $additional,
                            $prefix,
                            $suffix
                            ) = explode(';', $value);

                        $cardData->setLastName($lastname);
                        $cardData->setFirstName($firstname);
                        $cardData->setAdditional($additional);
                        $cardData->setPrefix($prefix);
                        $cardData->setSuffix($suffix);
                        break;
                    case 'BDAY':
                        $cardData->setBirthday(new \DateTime($value));
                        break;
                    case 'ADR':
                        $key = $this->parseKey($types, 'WORK;POSTAL');
                        $address = new VCardAddress();
                        $address->parseAddress('4.0', $key, $value);
                        $cardData->addAddress($key, $address);
                        break;
                    case 'TEL':
                        $key = $this->parseKey($types);
                        $cardData->addPhone($key, $value);
                        break;
                    case 'EMAIL':
                        $key = $this->parseKey($types);
                        $cardData->addEmail($key, $value);
                        break;
                    case 'REV':
                        $cardData->setRevision($value);
                        break;
                    case 'VERSION':
                        $cardData->setVersion($value);
                        break;
                    case 'ORG':
                        $cardData->setOrganization($value);
                        break;
                    case 'URL':
                        $key = $this->parseKey($types);
                        $cardData->addUrl($key, $value);
                        break;
                    case 'TITLE':
                        $cardData->setTitle($value);
                        break;
                    case 'PHOTO':
                        if ($rawValue) {
                            $cardData->setRawPhoto($value);
                        } else {
                            $cardData->setPhoto($value);
                        }
                        break;
                    case 'LOGO':
                        if ($rawValue) {
                            $cardData->setRawLogo($value);
                        } else {
                            $cardData->setLogo($value);
                        }
                        break;
                    case 'NOTE':
                        $cardData->setNote($this->unescape($value));
                        break;
                    case 'CATEGORIES':
                        $cardData->setCategories(array_map('trim', explode(',', $value)));
                        break;
                }
            }
        }
    }

    /**
     * @param array  $types
     * @param string $default
     *
     * @return string
     */
    protected function parseKey(array $types, string $default = 'default'): string
    {
        return !empty($types) ? implode(';', $types) : $default;
    }

    /**
     * Unescape newline characters according to RFC2425 section 5.8.4.
     * This function will replace escaped line breaks with PHP_EOL.
     *
     * @link http://tools.ietf.org/html/rfc2425#section-5.8.4
     * @param string $text
     * @return string
     */
    protected function unescape($text): string
    {
        return str_replace("\\n", PHP_EOL, $text);
    }
}
