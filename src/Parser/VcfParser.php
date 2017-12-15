<?php

namespace JeroenDesloovere\VCard\Parser;

use JeroenDesloovere\VCard\Exception\VCardException;
use JeroenDesloovere\VCard\Property\Address;
use JeroenDesloovere\VCard\Property\FullName;
use JeroenDesloovere\VCard\Property\Name;
use JeroenDesloovere\VCard\Property\Note;
use JeroenDesloovere\VCard\Property\Parameter\Type;
use JeroenDesloovere\VCard\VCard;

class VcfParser implements ParserInterface
{
    /**
     * @param string $content
     * @return VCard[]
     * @throws VCardException
     */
    public function getVCards(string $content): array
    {
        // Normalize new lines.
        $content = str_replace(["\r\n", "\r"], "\n", $content);

        $content = trim($content);

        if (!preg_match('/^BEGIN:VCARD[\s\S]+END:VCARD$/', $content)) {
            throw VCardException::forNotAVCard($content);
        }

        // Remove first BEGIN:VCARD and last END:VCARD
        $content = substr($content, 12, -10);

        // RFC2425 5.8.1. Line delimiting and folding
        // Unfolding is accomplished by regarding CRLF immediately followed by
        // a white space character (namely HTAB ASCII decimal 9 or. SPACE ASCII
        // decimal 32) as equivalent to no characters at all (i.e., the CRLF
        // and single white space character are removed).
        $content = preg_replace("/\n(?:[ \t])/", '', $content);

        // If multiple vcards split per vcard
        $cardsContent = preg_split('/\nEND:VCARD\s+BEGIN:VCARD\n/', $content);

        $vCards = [];

        foreach ($cardsContent as $cardContent) {
            $vCards[] = $this->parseCard($cardContent);
        }

        return $vCards;
    }

    protected function parseCard(string $cardContent): VCard
    {
        $vCard = new VCard();

        $lines = explode("\n", $cardContent);

        // Parse the VCard, line by line.
        foreach ($lines as $line) {
            $vCard = $this->parseLine($vCard, $line);
        }

        return $vCard;
    }

    protected function parseLine(VCard $vCard, string $line): VCard
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
                $types = array_map(function ($type) {
                    return preg_replace('/^TYPE=/i', '', $type);
                }, $types);
            }

            $rawValue = false;
            foreach ($types as $i => $type) {
                if (false !== stripos($type, 'base64')) {
                    $value = base64_decode($value);
                    unset($types[$i]);
                    $rawValue = true;
                } elseif (false !== stripos($type, 'encoding=b')) {
                    $value = base64_decode($value);
                    unset($types[$i]);
                    $rawValue = true;
                } elseif (false !== stripos($type, 'quoted-printable')) {
                    $value = quoted_printable_decode($value);
                    unset($types[$i]);
                    $rawValue = true;
                } elseif (stripos($type, 'charset=') === 0) {
                    $encoding = substr($type, 8);

                    if (\in_array($encoding, mb_list_encodings(), true)) {
                        $value = mb_convert_encoding($value, 'UTF-8', $encoding);
                    }

                    unset($types[$i]);
                }
            }

            $vCard = $this->parseOutput($vCard, $element, $value, $types, $rawValue);
        }

        return $vCard;
    }

    protected function parseOutput(VCard $vCard, string $element, string $value, array $types, bool $rawValue): VCard
    {
        switch (strtoupper($element)) {
            case 'FN':
                $vCard->add(FullName::fromVcfString($value));

                break;
            case 'N':
                $vCard->add(Name::fromVcfString($value));

                break;
            /*
            case 'BDAY':
                $vCard->birthday = $this->parseBirthday($value);
                break;
            */
            case 'ADR':
                $address = Address::fromVcfString($value);
                $address->setType(new Type($types[0]));
                $vCard->add($address);

                break;
            /*
            case 'TEL':
                if (!isset($vCard->phone)) {
                    $vCard->phone = [];
                }
                $key = !empty($types) ? implode(';', $types) : 'default';
                $vCard->phone[$key][] = $value;
                break;
            case 'EMAIL':
                if (!isset($vCard->email)) {
                    $vCard->email = [];
                }
                $key = !empty($types) ? implode(';', $types) : 'default';
                $vCard->email[$key][] = $value;
                break;
            case 'REV':
                $vCard->revision = $value;
                break;
            case 'VERSION':
                $vCard->version = $value;
                break;
            case 'ORG':
                $vCard->organization = $value;
                break;
            case 'URL':
                if (!isset($vCard->url)) {
                    $vCard->url = [];
                }
                $key = !empty($types) ? implode(';', $types) : 'default';
                $vCard->url[$key][] = $value;
                break;
            case 'TITLE':
                $vCard->title = $value;
                break;
            case 'PHOTO':
                if ($rawValue) {
                    $vCard->rawPhoto = $value;
                } else {
                    $vCard->photo = $value;
                }
                break;
            case 'LOGO':
                if ($rawValue) {
                    $vCard->rawLogo = $value;
                } else {
                    $vCard->logo = $value;
                }
                break;
            */
            case 'NOTE':
                $vCard->add(Note::fromVcfString($this->unescape($value)));

                break;
            /*
            case 'CATEGORIES':
                $vCard->categories = array_map('trim', explode(',', $value));
                break;
            */
        }

        return $vCard;
    }

    // @todo
    // public function unfold()

    /**
     * Unescape newline characters according to RFC2425 section 5.8.4.
     * This function will replace escaped line breaks with PHP_EOL.
     *
     * @link http://tools.ietf.org/html/rfc2425#section-5.8.4
     * @param  string $text
     * @return string
     */
    protected function unescape($text): string
    {
        return str_replace("\\n", PHP_EOL, $text);
    }
}
