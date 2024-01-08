<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard;

use DateTimeImmutable;

interface VCardInterface
{
    public const VCARD_START = 'BEGIN:VCARD';

    public const VCARD_END = 'END:VCARD';

    public const VCARD_VERSION = 'VERSION';

    public const VCARD_REVISION = 'REV';

    public const VCARD_VERSION_VALUE = '4.0';

    public function addAddress(
        string $name = '',
        string $extended = '',
        string $street = '',
        string $city = '',
        string $region = '',
        string $zip = '',
        string $country = '',
        array $type = ['WORK', 'POSTAL'],
    ): void;

    public function addBirthday(DateTimeImmutable $date): void;

    public function addCompany(string $company, string $department = ''): void;

    public function addEmail(string $address, array $type = []): void;

    public function addJobtitle(string $jobtitle): void;

    public function addLabel(string $label, string $type = ''): void;

    public function addRole(string $role): void;

    public function addName(
        string $lastName = '',
        string $firstName = '',
        string $additional = '',
        string $prefix = '',
        string $suffix = '',
    ): void;

    public function addNote(string $note): void;

    public function addCategories(array $categories): void;

    public function addPhoneNumber(string $number, array $type = []): void;

    public function addLogo(string $url, bool $include = true): void;

    public function addLogoContent(string $content): void;

    public function addPhoto(string $url, bool $include = true): void;

    public function addPhotoContent(string $content): void;

    public function addURL(string $url, string $type = ''): void;

    public function buildVCard(): string;

    public function buildVCalendar(): string;

    public function download(): string;

    /**
     * @deprecated use getOutput()
     */
    public function get(): string;

    public function getCharset(): string;

    public function getCharsetString(): string;

    public function getContentType(): string;

    public function getFilename(): string;

    public function getFileExtension(): string;

    public function getHeaders(bool $asAssociative): array;

    public function getOutput(): string;

    public function getProperties(): array;

    public function hasProperty(string $key): bool;

    public function isIOS(): bool;

    public function isIOS7(): bool;

    public function save(): void;

    public function setCharset(string $charset): void;

    public function setFilename(string|array $value, bool $overwrite = true, string $separator = '_'): void;

    public function setSavePath(string $savePath): void;
}
