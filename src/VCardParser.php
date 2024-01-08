<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard;

use DateTimeImmutable;
use Iterator;
use JeroenDesloovere\VCard\Dto\AddressData;
use JeroenDesloovere\VCard\Dto\CardData;
use Webmozart\Assert\Assert;

class VCardParser implements Iterator
{
    protected array $vcardObjects = [];

    protected int $position;

    public static function parseFromFile(string $filename): self
    {
        Assert::fileExists($filename);
        Assert::readable($filename);

        $content = file_get_contents($filename);
        Assert::notEmpty($content);

        return new self($content);
    }

    public function __construct(private string $content)
    {
        $this->vcardObjects = [];
        $this->rewind();
        $this->parse();
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function current(): CardData
    {
        Assert::notFalse($this->valid(), 'Current card should be valid, malformed input.');

        return $this->getCardAtIndex($this->position);
    }

    public function key(): int
    {
        return $this->position;
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function valid(): bool
    {
        return !empty($this->vcardObjects[$this->position]);
    }

    public function getCards(): array
    {
        return $this->vcardObjects;
    }

    public function getCardAtIndex(int $i): CardData
    {
        Assert::keyExists($this->vcardObjects, $i);

        return $this->vcardObjects[$i];
    }

    protected function parse(): void
    {
        $content = $this->content;
        Assert::string($content);
        $content = str_replace(["\r\n", "\r"], "\n", $content);
        Assert::string($content);
        $content = preg_replace("/\n(?:[ \t])/", '', $content);
        Assert::string($content);
        $lines = array_filter(explode("\n", $content));

        $cardData = null;
        foreach ($lines as $line) {
            $line = trim($line);
            if (strtoupper($line) === VCardInterface::VCARD_START) {
                $cardData = new CardData();
            } elseif (strtoupper($line) === VCardInterface::VCARD_END) {
                Assert::notNull($cardData, 'Card data should not be null, malformed input.');
                $this->vcardObjects[] = $cardData;
            } elseif (!empty($line)) {
                $line = preg_replace('/^\w+\./', '', $line);
                Assert::notNull($line, 'Line should not be null, malformed input.');
                Assert::contains($line, ':', 'Line should contain a colon, malformed input.');
                [$type, $value] = explode(':', $line, 2);
                $types = explode(';', $type);
                $element = strtoupper($types[0]);
                array_shift($types);

                if (empty($types) === false) {
                    $types = array_map(static fn ($type) => preg_replace('/^type=/i', '', $type), $types);
                }

                $i = 0;
                $rawValue = false;
                foreach ($types as $type) {
                    Assert::string($type, 'Type should be a string, malformed input.');
                    if (str_contains(strtolower($type), 'base64') || str_contains(strtolower($type), 'encoding=b')) {
                        $value = base64_decode($value);
                        unset($types[$i]);
                        $rawValue = true;
                    } elseif (str_contains(strtolower($type), 'quoted-printable')) {
                        $value = quoted_printable_decode($value);
                        unset($types[$i]);
                        $rawValue = true;
                    } elseif (str_starts_with(strtolower($type), 'charset=')) {
                        try {
                            $value = mb_convert_encoding($value, 'UTF-8', substr($type, 8));
                        } finally {
                            unset($types[$i]);
                        }
                    }

                    ++$i;
                }

                Assert::isInstanceOf($cardData, CardData::class, 'Card data should be an instance of CardData, malformed input.');
                $this->processElement(
                    $element,
                    $value,
                    $cardData,
                    $types,
                    $rawValue,
                );
            }
        }
    }

    private function parseName(string $value): array
    {
        $value = explode(';', $value);
        $keys = ['lastname', 'firstname', 'additional', 'prefix', 'suffix'];
        $value = array_pad($value, count($keys), '');

        return array_combine($keys, $value);
    }

    private function parseBirthday(string $value): DateTimeImmutable
    {
        return new \DateTimeImmutable($value);
    }

    private function parseAddress(string $value): array
    {
        $value = explode(';', $value);
        $keys = ['name', 'extended', 'street', 'city', 'region', 'zip', 'country'];
        $value = array_pad($value, count($keys), '');

        return array_combine($keys, $value);
    }

    /**
     * @see http://tools.ietf.org/html/rfc2425#section-5.8.4
     */
    protected function unescape(string $text): string
    {
        return str_replace('\\n', \PHP_EOL, $text);
    }

    private function processElement(
        string $element,
        array|false|string|null $value,
        CardData $cardData,
        array $types,
        bool $rawValue,
    ): void {
        Assert::string($value, 'Value should be a string, malformed input.');

        switch (strtoupper($element)) {
            case 'FN':
                $cardData->setName($value);

                break;
            case 'N':
                $nameData = $this->parseName($value);
                $cardData->setLastName($nameData['lastname']);
                $cardData->setFirstName($nameData['firstname']);
                $cardData->setAdditional($nameData['additional']);
                $cardData->setPrefix($nameData['prefix']);
                $cardData->setSuffix($nameData['suffix']);

                break;
            case 'BDAY':
                $cardData->setBirthday($this->parseBirthday($value));

                break;
            case 'ADR':
                $key = array_filter($types) !== []
                    ? implode(';', $types)
                    : 'WORK;POSTAL';

                $address = new AddressData(...array_values($this->parseAddress($value)));
                $cardData->addAddress($key, $address);

                break;
            case 'TEL':
                $key = array_filter($types) !== []
                    ? implode(';', $types)
                    : 'default';

                $cardData->addPhone($key, $value);

                break;
            case 'EMAIL':
                $key = array_filter($types) !== []
                    ? implode(';', $types)
                    : 'default';

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
                $key = array_filter($types) !== []
                    ? implode(';', $types)
                    : 'default';

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
                $cardData->setCategories(array_map(static fn ($v) => trim($v), explode(',', $value)));

                break;
            case 'LABEL':
                $cardData->setLabel($value);

                break;
        }
    }
}
