<?php

namespace JeroenDesloovere\VCard;

/*
 * This file is part of the VCard PHP Class from Jeroen Desloovere.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use JeroenDesloovere\VCard\Exception\InvalidVersionException;
use JeroenDesloovere\VCard\Model\VCard;
use JeroenDesloovere\VCard\Model\VCardAddress;
use JeroenDesloovere\VCard\Model\VCardMedia;
use JeroenDesloovere\VCard\Util\GeneralUtil;

/**
 * VCard PHP Class to parse .vcard files.
 *
 * This class is heavily based on the Zendvcard project (seemingly abandoned),
 * which is licensed under the Apache 2.0 license.
 * More information can be found at https://code.google.com/archive/p/zendvcard/
 */
class VCardParser
{
    /**
     * The raw VCard content.
     *
     * @var string
     */
    protected $content;

    /**
     * The raw VCard content.
     *
     * @var string[]
     */
    protected $cardsContent;

    /**
     * The VCard data objects.
     *
     * @var array
     */
    protected $vcardObjects;

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
        $this->parse();
    }

    /**
     * Helper function to parse a file directly.
     *
     * @param string $filename
     *
     * @return self
     * @throws \RuntimeException
     */
    public static function parseFromFile(string $filename): VCardParser
    {
        if (file_exists($filename) && is_readable($filename)) {
            return new self(file_get_contents($filename));
        }

        throw new \RuntimeException(sprintf("File %s is not readable, or doesn't exist.", $filename));
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
    public function getCardAtIndex(int $i): VCard
    {
        if (isset($this->vcardObjects[$i])) {
            return $this->vcardObjects[$i];
        }

        throw new \OutOfBoundsException();
    }

    /**
     * @param int $i
     *
     * @return bool
     */
    public function hasCardAtIndex(int $i): bool
    {
        return !empty($this->vcardObjects[$i]);
    }

    /**
     * Start the parsing process.
     *
     * This method will populate the data object.
     *
     * @throws InvalidVersionException
     */
    protected function parse(): void
    {
        // Normalize new lines.
        $this->content = str_replace(["\r\n", "\r"], "\n", $this->content);

        $this->content = trim($this->content);

        $this->content = substr($this->content, 12, -10);

        // RFC2425 5.8.1. Line delimiting and folding
        // Unfolding is accomplished by regarding CRLF immediately followed by
        // a white space character (namely HTAB ASCII decimal 9 or. SPACE ASCII
        // decimal 32) as equivalent to no characters at all (i.e., the CRLF
        // and single white space character are removed).
        $this->content = preg_replace("/\n(?:[ \t])/", '', $this->content);

        $this->cardsContent = preg_split('/\nEND:VCARD\s+BEGIN:VCARD\n/', $this->content);

        foreach ($this->cardsContent as $cardContent) {
            $this->parseCard($cardContent);
        }
    }

    /**
     * @param string $cardContent
     *
     * @throws InvalidVersionException
     */
    protected function parseCard(string $cardContent): void
    {
        $cardData = new VCard();

        $lines = explode("\n", $cardContent);

        // Parse the VCard, line by line.
        foreach ($lines as $line) {
            $cardData = $this->parseLine($cardData, $line);
        }

        $this->vcardObjects[] = $cardData;
    }

    /**
     * @param VCard  $cardData
     * @param string $line
     *
     * @return VCard
     * @throws InvalidVersionException
     */
    protected function parseLine(VCard $cardData, string $line): VCard
    {
        $line = trim($line);

        if (!empty($line)) {
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
                $types = array_map(
                    function ($type) {
                        return preg_replace('/^type=/i', '', $type);
                    },
                    $types
                );
            }

            $rawValue = false;
            foreach ($types as $i => $type) {
                if (false !== stripos($type, 'base64')) {
                    $value = base64_decode($value);
                    unset($types[$i]);
                    $rawValue = true;
                } elseif (preg_match('/encoding=b/i', $type)) {
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

            $cardData = $this->parseOutput($cardData, $element, $value, $types, $rawValue);
        }

        return $cardData;
    }

    /**
     * @param VCard  $cardData
     * @param string $element
     * @param string $value
     * @param array  $types
     * @param bool   $rawValue
     *
     * @return VCard
     * @throws InvalidVersionException
     */
    protected function parseOutput(VCard $cardData, string $element, string $value, array $types, bool $rawValue): VCard
    {
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
                $key = GeneralUtil::parseKey($types, 'WORK;POSTAL');
                $address = new VCardAddress();
                $address->parser('4.0', $key, $value);
                $cardData->addAddress($address, $key);
                break;
            case 'TEL':
                $key = GeneralUtil::parseKey($types);
                $cardData->addPhone($value, $key);
                break;
            case 'EMAIL':
                $key = GeneralUtil::parseKey($types);
                $cardData->addEmail($value, $key);
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
                $key = GeneralUtil::parseKey($types);
                $cardData->addUrl($value, $key);
                break;
            case 'TITLE':
                $cardData->setTitle($value);
                break;
            case 'PHOTO':
                $media = new VCardMedia();
                $media->parser($value, $rawValue);
                $cardData->setPhoto($media);
                break;
            case 'LOGO':
                $media = new VCardMedia();
                $media->parser($value, $rawValue);
                $cardData->setLogo($media);
                break;
            case 'NOTE':
                $cardData->setNote(GeneralUtil::unescape($value));
                break;
            case 'CATEGORIES':
                $cardData->setCategories(array_map('trim', explode(',', $value)));
                break;
        }

        return $cardData;
    }
}
