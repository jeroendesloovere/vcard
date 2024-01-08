<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Dto;

use DateTimeImmutable;

final class CardData
{
    private string $name = '';

    private string $firstName = '';

    private string $lastName = '';

    private string $additional = '';

    private string $prefix = '';

    private string $suffix = '';

    private string $title = '';

    private array $phone = [];

    private array $email = [];

    private array $address = [];

    private string $website = '';

    private string $photo = '';

    private string $rawPhoto = '';

    private string $logo = '';

    private string $rawLogo = '';

    private array $url = [];

    private array $categories = [];

    private string $label = '';

    private string $note = '';

    private string $revision = '';

    private string $version = '';

    private string $organization = '';

    private DateTimeImmutable $birthday;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getPhone(): array
    {
        return $this->phone;
    }

    public function addPhone(string $key, string $phone): void
    {
        $this->phone[$key][] = $phone;
    }

    public function getEmails(): array
    {
        return $this->email;
    }

    public function addEmail(string $key, string $email): void
    {
        $this->email[$key][] = $email;
    }

    public function getAddress(): array
    {
        return $this->address;
    }

    public function addAddress(string $key, ?AddressData $address): void
    {
        $this->address[$key][] = $address;
    }

    public function getWebsite(): string
    {
        return $this->website;
    }

    public function setWebsite(string $website): void
    {
        $this->website = $website;
    }

    public function getPhoto(): string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): void
    {
        $this->photo = $photo;
    }

    public function getBirthday(): DateTimeImmutable
    {
        return $this->birthday;
    }

    public function setBirthday(DateTimeImmutable $birthday): void
    {
        $this->birthday = $birthday;
    }

    public function getUrls(): array
    {
        return $this->url;
    }

    public function addUrl(string $key, string $url): void
    {
        $this->url[$key][] = $url;
    }

    public function getLogo(): string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): void
    {
        $this->logo = $logo;
    }

    public function getRawPhoto(): string
    {
        return $this->rawPhoto;
    }

    public function setRawPhoto(string $rawPhoto): void
    {
        $this->rawPhoto = $rawPhoto;
    }

    public function getRawLogo(): string
    {
        return $this->rawLogo;
    }

    public function setRawLogo(string $rawLogo): void
    {
        $this->rawLogo = $rawLogo;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function setCategories(array $categories): void
    {
        $this->categories = $categories;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function getRevision(): string
    {
        return $this->revision;
    }

    public function setRevision(string $revision): void
    {
        $this->revision = $revision;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    public function getOrganization(): string
    {
        return $this->organization;
    }

    public function setOrganization(string $organization): void
    {
        $this->organization = $organization;
    }

    public function getNote(): string
    {
        return $this->note;
    }

    public function setNote(string $note): void
    {
        $this->note = $note;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getAdditional(): string
    {
        return $this->additional;
    }

    public function setAdditional(string $additional): void
    {
        $this->additional = $additional;
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function setPrefix(string $prefix): void
    {
        $this->prefix = $prefix;
    }

    public function getSuffix(): string
    {
        return $this->suffix;
    }

    public function setSuffix(string $suffix): void
    {
        $this->suffix = $suffix;
    }
}
