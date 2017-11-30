<?php

namespace JeroenDesloovere\VCard\Service;

use Behat\Transliterator\Transliterator;
use JeroenDesloovere\VCard\Exception\ElementAlreadyExistsException;
use JeroenDesloovere\VCard\Model\VCard;
use JeroenDesloovere\VCard\Model\VCardAddress;
use JeroenDesloovere\VCard\Model\VCardMedia;

/**
 * Class PropertyService
 *
 * @package JeroenDesloovere\VCard\Util
 */
class PropertyService
{
    /**
     * definedElements
     *
     * @var array
     */
    private $definedElements;

    /**
     * Filename
     *
     * @var string|null
     */
    private $filename;

    /**
     * Multiple properties for element allowed
     *
     * @var array
     */
    private static $multiplePropertiesForElementAllowed = [
        'email',
        'address',
        'phoneNumber',
        'url',
    ];

    /**
     * Properties
     *
     * @var array
     */
    private $properties = [];

    /**
     * Default Charset
     *
     * @var string
     */
    private $charset;

    /**
     * @var VCard[]
     */
    private $vCards;

    /**
     * PropertyService constructor.
     *
     * @param VCard|VCard[] $vCard
     * @param string        $charset
     *
     * @throws ElementAlreadyExistsException
     */
    public function __construct($vCard, $charset = 'utf-8')
    {
        $this->vCards = $vCard;
        if (!\is_array($vCard)) {
            $this->vCards = [$vCard];
        }

        $this->charset = $charset;

        $this->parseVCarts();
    }

    /**
     * Get filename
     *
     * @return string|null
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * Get properties
     *
     * @return array
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * Get charset string
     *
     * @return string
     */
    private function getCharsetString(): string
    {
        $charsetString = '';

        if ($this->charset === 'utf-8') {
            $charsetString = ';CHARSET='.$this->charset;
        }

        return $charsetString;
    }

    /**
     * Set filename
     *
     * @param string|array $value
     * @param bool         $overwrite [optional] Default overwrite is true
     * @param string       $separator [optional] Default separator is an underscore '_'
     * @return void
     */
    private function setFilename($value, $overwrite = true, $separator = '_'): void
    {
        // recast to string if $value is array
        if (\is_array($value)) {
            $value = implode($separator, $value);
        }

        // trim unneeded values
        $value = trim($value, $separator);

        // remove all spaces
        $value = preg_replace('/\s+/', $separator, $value);

        // if value is empty, stop here
        if (empty($value)) {
            return;
        }

        // decode value
        $value = Transliterator::transliterate($value);

        // lowercase the string
        $value = strtolower($value);

        // urlize this part
        $value = Transliterator::urlize($value);

        // overwrite filename or add to filename using a prefix in between
        $this->filename = $overwrite ?
            $value : $this->filename.$separator.$value;
    }

    /**
     * Has property
     *
     * @param string $key
     * @return bool
     */
    private function hasProperty(string $key): bool
    {
        $properties = $this->getProperties();

        foreach ($properties as $property) {
            if ($property['key'] === $key && $property['value'] !== '') {
                return true;
            }
        }

        return false;
    }

    /**
     * @throws ElementAlreadyExistsException
     */
    private function parseVCarts(): void
    {
        foreach ($this->vCards as $vCard) {
            $this->parseVCart($vCard);
        }
    }

    /**
     * @param VCard $vCard
     *
     * @throws ElementAlreadyExistsException
     */
    private function parseVCart(VCard $vCard): void
    {
        $this->addAddress($vCard->getAddresses());
        $this->addBirthday($vCard->getBirthday());
        $this->addOrganization($vCard->getOrganization());
        $this->setArrayProperty('email', 'EMAIL;INTERNET', $vCard->getEmails());
        $this->setStringProperty('title', 'TITLE', $vCard->getTitle());
        $this->setStringProperty('role', 'ROLE', null); // TODO add Role to \JeroenDesloovere\VCard\Model\VCard
        $this->addName($vCard->getLastName(), $vCard->getFirstName(), $vCard->getAdditional(), $vCard->getPrefix(), $vCard->getSuffix());
        $this->setStringProperty('note', 'NOTE', $vCard->getNote());
        $this->addCategories($vCard->getCategories());
        $this->setArrayProperty('phoneNumber', 'TEL', $vCard->getPhones());
        $this->setMedia('logo', 'LOGO', $vCard->getLogo());
        $this->setMedia('photo', 'PHOTO', $vCard->getPhoto());
        $this->setArrayProperty('url', 'URL', $vCard->getUrls());
    }

    /**
     * @param VCardAddress[][]|null $addresses
     *
     * @throws ElementAlreadyExistsException
     */
    private function addAddress($addresses): void
    {
        if ($addresses !== null) {
            foreach ($addresses as $type => $sub) {
                foreach ($sub as $address) {
                    $this->setProperty(
                        'address',
                        'ADR'.(($type !== '') ? ';'.$type : '').$this->getCharsetString(),
                        $address->getAddress()
                    );
                }
            }
        }
    }

    /**
     * Add birthday
     *
     * @param \DateTime|null $date Format is YYYY-MM-DD
     *
     * @throws ElementAlreadyExistsException
     */
    private function addBirthday(?\DateTime $date): void
    {
        if ($date !== null) {
            $this->setProperty(
                'birthday',
                'BDAY'.$this->getCharsetString(),
                $date->format('Y-m-d')
            );
        }
    }

    /**
     * Add company
     *
     * @param null|string $company
     * @param string      $department
     *
     * @throws ElementAlreadyExistsException
     */
    private function addOrganization(?string $company, string $department = ''): void
    {
        if ($company !== null) {
            $this->setProperty(
                'organization',
                'ORG'.$this->getCharsetString(),
                $company.($department !== '' ? ';'.$department : '')
            );

            // if filename is empty, add to filename
            if ($this->filename === null) {
                $this->setFilename($company);
            }
        }
    }

    /**
     * Add name
     *
     * @param string $lastName   [optional]
     * @param string $firstName  [optional]
     * @param string $additional [optional]
     * @param string $prefix     [optional]
     * @param string $suffix     [optional]
     *
     * @throws ElementAlreadyExistsException
     */
    private function addName(
        ?string $lastName = '',
        ?string $firstName = '',
        ?string $additional = '',
        ?string $prefix = '',
        ?string $suffix = ''
    ): void {
        if ($lastName !== null) {
            // define values with non-empty values
            $values = array_filter(
                [
                    $prefix,
                    $firstName,
                    $additional,
                    $lastName,
                    $suffix,
                ]
            );

            // define filename
            $this->setFilename($values);

            // set property
            $property = $lastName.';'.$firstName.';'.$additional.';'.$prefix.';'.$suffix;
            $this->setProperty(
                'name',
                'N'.$this->getCharsetString(),
                $property
            );

            // is property FN set?
            if (!$this->hasProperty('FN'.$this->getCharsetString())) {
                // set property
                $this->setProperty(
                    'fullname',
                    'FN'.$this->getCharsetString(),
                    trim(implode(' ', $values))
                );
            }
        }
    }

    /**
     * Add categories
     *
     * @param null|array $categories
     *
     * @throws ElementAlreadyExistsException
     */
    private function addCategories(?array $categories): void
    {
        if ($categories !== null) {
            $this->setProperty(
                'categories',
                'CATEGORIES'.$this->getCharsetString(),
                trim(implode(',', $categories))
            );
        }
    }

    /**
     * Add Array
     *
     * @param string          $element
     * @param string          $property
     * @param null|string[][] $values
     *
     * @throws ElementAlreadyExistsException
     */
    private function setArrayProperty(string $element, string $property, $values): void
    {
        if ($values !== null) {
            foreach ($values as $type => $sub) {
                foreach ($sub as $url) {
                    $this->setProperty(
                        $element,
                        $property.(($type !== '') ? ';'.$type : '').$this->getCharsetString(),
                        $url
                    );
                }
            }
        }
    }

    /**
     * Set string property
     *
     * @param string      $element
     * @param string      $property
     * @param null|string $value
     *
     * @throws ElementAlreadyExistsException
     */
    private function setStringProperty(string $element, string $property, ?string $value): void
    {
        if ($value !== null) {
            $this->setProperty(
                $element,
                $property.$this->getCharsetString(),
                $value
            );
        }
    }

    /**
     * Set Media
     *
     * @param string          $element
     * @param string          $property
     * @param VCardMedia|null $media
     *
     * @throws ElementAlreadyExistsException
     */
    private function setMedia(string $element, string $property, ?VCardMedia $media): void
    {
        if ($media !== null) {
            $result = [];

            if ($media->getUrl() !== null) {
                $result = $media->builderUrl($property);
            }

            if ($media->getRaw() !== null) {
                $result = $media->builderRaw($property);
            }

            if ($media->getUrl() !== null || $media->getRaw() !== null) {
                $this->setProperty(
                    $element,
                    $result['key'],
                    $result['value']
                );
            }
        }
    }

    /**
     * Set property
     *
     * @param string $element The element name you want to set, f.e.: name, email, phoneNumber, ...
     * @param string $key
     * @param string $value
     *
     * @throws ElementAlreadyExistsException
     */
    private function setProperty(string $element, string $key, string $value): void
    {
        if (isset($this->definedElements[$element])
            && !\in_array($element, $this::$multiplePropertiesForElementAllowed, true)) {
            throw new ElementAlreadyExistsException($element);
        }

        // we define that we set this element
        $this->definedElements[$element] = true;

        // adding property
        $this->properties[] = [
            'key' => $key,
            'value' => $value,
        ];
    }
}
