<?php

namespace JeroenDesloovere\VCard;

/*
 * This file is part of the VCard PHP Class from Jeroen Desloovere.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Behat\Transliterator\Transliterator;
use JeroenDesloovere\VCard\Exception\ElementAlreadyExistsException;
use JeroenDesloovere\VCard\Exception\EmptyUrlException;
use JeroenDesloovere\VCard\Exception\InvalidImageException;
use JeroenDesloovere\VCard\Exception\OutputDirectoryNotExistsException;
use JeroenDesloovere\VCard\Model\VCard;
use JeroenDesloovere\VCard\Model\VCardAddress;
use JeroenDesloovere\VCard\Model\VCardMedia;
use JeroenDesloovere\VCard\Util\GeneralUtil;

/**
 * VCard PHP Class to generate .vcard files and save them to a file or output as a download.
 */
class VCardBuilder
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
     * @var string
     */
    private $filename;

    /**
     * Save Path
     *
     * @var string
     */
    private $savePath;

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
    private $properties;

    /**
     * Default Charset
     *
     * @var string
     */
    public $charset = 'utf-8';

    /**
     * @var VCard[]
     */
    private $vCards;

    /**
     * VCardBuilder constructor.
     *
     * @param VCard|VCard[] $vCard
     *
     * @throws ElementAlreadyExistsException
     * @throws EmptyUrlException
     * @throws InvalidImageException
     */
    public function __construct($vCard)
    {
        $this->vCards = $vCard;
        if (!\is_array($vCard)) {
            $this->vCards = [$vCard];
        }
        $this->parseVCarts();
    }

    /**
     * Build VCard (.vcf)
     *
     * @return string
     */
    public function buildVCard(): string
    {
        // init string
        $string = "BEGIN:VCARD\r\n";
        $string .= "VERSION:3.0\r\n";
        $string .= 'REV:'.date('Y-m-d').'T'.date('H:i:s')."Z\r\n";

        // loop all properties
        $properties = $this->getProperties();
        foreach ($properties as $property) {
            // add to string
            $string .= GeneralUtil::fold($property['key'].':'.GeneralUtil::escape($property['value'])."\r\n");
        }

        // add to string
        $string .= "END:VCARD\r\n";

        // return
        return $string;
    }

    /**
     * Build VCalender (.ics) - Safari (< iOS 8) can not open .vcf files, so we have build a workaround.
     *
     * @return string
     */
    public function buildVCalendar(): string
    {
        // init dates
        $dtstart = date('Ymd').'T'.date('Hi').'00';
        $dtend = date('Ymd').'T'.date('Hi').'01';

        // init string
        $string = "BEGIN:VCALENDAR\n";
        $string .= "VERSION:2.0\n";
        $string .= "BEGIN:VEVENT\n";
        $string .= 'DTSTART;TZID=Europe/London:'.$dtstart."\n";
        $string .= 'DTEND;TZID=Europe/London:'.$dtend."\n";
        $string .= "SUMMARY:Click attached contact below to save to your contacts\n";
        $string .= 'DTSTAMP:'.$dtstart."Z\n";
        $string .= "ATTACH;VALUE=BINARY;ENCODING=BASE64;FMTTYPE=text/directory;\n";
        $string .= ' X-APPLE-FILENAME='.$this->getFilename().'.'.$this->getFileExtension().":\n";

        // base64 encode it so that it can be used as an attachemnt to the "dummy" calendar appointment
        $b64vcard = base64_encode($this->buildVCard());

        // chunk the single long line of b64 text in accordance with RFC2045
        // (and the exact line length determined from the original .ics file exported from Apple calendar
        $b64mline = chunk_split($b64vcard, 74, "\n");

        // need to indent all the lines by 1 space for the iphone (yes really?!!)
        $b64final = preg_replace('/(.+)/', ' $1', $b64mline);
        $string .= $b64final;

        // output the correctly formatted encoded text
        $string .= "END:VEVENT\n";
        $string .= "END:VCALENDAR\n";

        // return
        return $string;
    }

    /**
     * Download a vcard or vcal file to the browser.
     */
    public function download(): void
    {
        // define output
        $output = $this->getOutput();

        foreach ($this->getHeaders(false) as $header) {
            header($header);
        }

        // echo the output and it will be a download
        echo $output;
    }

    /**
     * Get charset
     *
     * @return string
     */
    public function getCharset(): string
    {
        return $this->charset;
    }

    /**
     * Get charset string
     *
     * @return string
     */
    public function getCharsetString(): string
    {
        $charsetString = '';

        if ($this->charset === 'utf-8') {
            $charsetString = ';CHARSET='.$this->charset;
        }

        return $charsetString;
    }

    /**
     * Get content type
     *
     * @return string
     */
    public function getContentType(): string
    {
        return GeneralUtil::isIOS7() ?
            'text/x-vcalendar' : 'text/x-vcard';
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename(): string
    {
        if (!$this->filename) {
            return 'unknown';
        }

        return $this->filename;
    }

    /**
     * Get file extension
     *
     * @return string
     */
    public function getFileExtension(): string
    {
        return GeneralUtil::isIOS7() ?
            'ics' : 'vcf';
    }

    /**
     * Get headers
     *
     * @param bool $asAssociative
     * @return array
     */
    public function getHeaders(bool $asAssociative): array
    {
        $contentType = $this->getContentType().'; charset='.$this->getCharset();
        $contentDisposition = 'attachment; filename='.$this->getFilename().'.'.$this->getFileExtension();
        $contentLength = mb_strlen($this->getOutput(), $this->getCharset());
        $connection = 'close';

        if ($asAssociative) {
            return [
                'Content-type' => $contentType,
                'Content-Disposition' => $contentDisposition,
                'Content-Length' => $contentLength,
                'Connection' => $connection,
            ];
        }

        return [
            'Content-type: '.$contentType,
            'Content-Disposition: '.$contentDisposition,
            'Content-Length: '.$contentLength,
            'Connection: '.$connection,
        ];
    }

    /**
     * Get output as string
     * iOS devices (and safari < iOS 8 in particular) can not read .vcf (= vcard) files.
     * So I build a workaround to build a .ics (= vcalender) file.
     *
     * @return string
     */
    public function getOutput(): string
    {
        $output = GeneralUtil::isIOS7() ?
            $this->buildVCalendar() : $this->buildVCard();

        return $output;
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
     * Has property
     *
     * @param string $key
     * @return bool
     */
    public function hasProperty(string $key): bool
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
     * Save to a file
     *
     * @return void
     */
    public function save(): void
    {
        $file = $this->getFilename().'.'.$this->getFileExtension();

        // Add save path if given
        if (null !== $this->savePath) {
            $file = $this->savePath.$file;
        }

        file_put_contents(
            $file,
            $this->getOutput()
        );
    }

    /**
     * Set charset
     *
     * @param string $charset
     * @return void
     */
    public function setCharset(string $charset): void
    {
        $this->charset = $charset;
    }

    /**
     * Set filename
     *
     * @param string|array $value
     * @param bool         $overwrite [optional] Default overwrite is true
     * @param string       $separator [optional] Default separator is an underscore '_'
     * @return void
     */
    public function setFilename($value, $overwrite = true, $separator = '_'): void
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
     * Set the save path directory
     *
     * @param string $savePath Save Path
     *
     * @throws OutputDirectoryNotExistsException
     */
    public function setSavePath($savePath): void
    {
        if (!is_dir($savePath)) {
            throw new OutputDirectoryNotExistsException();
        }

        // Add trailing directory separator the save path
        if (substr($savePath, -1) !== DIRECTORY_SEPARATOR) {
            $savePath .= DIRECTORY_SEPARATOR;
        }

        $this->savePath = $savePath;
    }

    /**
     * @throws ElementAlreadyExistsException
     * @throws EmptyUrlException
     * @throws InvalidImageException
     */
    protected function parseVCarts(): void
    {
        foreach ($this->vCards as $vCard) {
            $this->parseVCart($vCard);
        }
    }

    /**
     * @param VCard $vCard
     *
     * @throws ElementAlreadyExistsException
     * @throws EmptyUrlException
     * @throws InvalidImageException
     */
    protected function parseVCart(VCard $vCard): void
    {
        $this->addAddress($vCard->getAddresses());
        $this->addBirthday($vCard->getBirthday());
        $this->addOrganization($vCard->getOrganization());
        $this->addEmail($vCard->getEmails());
        $this->addTitle($vCard->getTitle());
        $this->addRole(null); // TODO add Role to \JeroenDesloovere\VCard\Model\VCard
        $this->addName($vCard->getLastName(), $vCard->getFirstName(), $vCard->getAdditional(), $vCard->getPrefix(), $vCard->getSuffix());
        $this->addNote($vCard->getNote());
        $this->addCategories($vCard->getCategories());
        $this->addPhoneNumber($vCard->getPhones());
        $this->addRawLogo($vCard->getLogo());
        $this->addLogo($vCard->getLogo(), false);
        $this->addRawPhoto($vCard->getPhoto());
        $this->addPhoto($vCard->getPhoto(), false);
        $this->addUrl($vCard->getUrls());
    }

    /**
     * @param VCardAddress[][]|null $addresses
     *
     * @throws ElementAlreadyExistsException
     */
    protected function addAddress($addresses): void
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
    protected function addBirthday(?\DateTime $date): void
    {
        if ($date !== null) {
            $this->setProperty(
                'birthday',
                'BDAY',
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
    protected function addOrganization(?string $company, string $department = ''): void
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
     * @param string[][]|null $addresses
     *
     * @throws ElementAlreadyExistsException
     */
    protected function addEmail($addresses): void
    {
        if ($addresses !== null) {
            foreach ($addresses as $type => $sub) {
                foreach ($sub as $address) {
                    $this->setProperty(
                        'email',
                        'EMAIL;INTERNET'.(($type !== '') ? ';'.$type : ''),
                        $address
                    );
                }
            }
        }
    }

    /**
     * Add title
     *
     * @param null|string $title The title for the person.
     *
     * @throws ElementAlreadyExistsException
     */
    protected function addTitle(?string $title): void
    {
        if ($title !== null) {
            $this->setProperty(
                'title',
                'TITLE'.$this->getCharsetString(),
                $title
            );
        }
    }

    /**
     * Add role
     *
     * @param null|string $role The role for the person.
     *
     * @throws ElementAlreadyExistsException
     */
    protected function addRole(?string $role): void
    {
        if ($role !== null) {
            $this->setProperty(
                'role',
                'ROLE'.$this->getCharsetString(),
                $role
            );
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
    protected function addName(
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
     * Add note
     *
     * @param null|string $note
     *
     * @throws ElementAlreadyExistsException
     */
    protected function addNote(?string $note): void
    {
        if ($note !== null) {
            $this->setProperty(
                'note',
                'NOTE'.$this->getCharsetString(),
                $note
            );
        }
    }

    /**
     * Add categories
     *
     * @param null|array $categories
     *
     * @throws ElementAlreadyExistsException
     */
    protected function addCategories(?array $categories): void
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
     * Add phone number
     *
     * @param null|string[][] $numbers
     *
     * @throws ElementAlreadyExistsException
     */
    protected function addPhoneNumber($numbers): void
    {
        if ($numbers !== null) {
            foreach ($numbers as $type => $sub) {
                foreach ($sub as $number) {
                    $this->setProperty(
                        'phoneNumber',
                        'TEL'.(($type !== '') ? ';'.$type : ''),
                        $number
                    );
                }
            }
        }
    }

    /**
     * Add Logo
     *
     * @param VCardMedia|null $media
     * @param bool            $include Include the image in our vcard?
     *
     * @throws ElementAlreadyExistsException
     * @throws EmptyUrlException
     * @throws InvalidImageException
     */
    protected function addLogo(?VCardMedia $media, bool $include = true): void
    {
        if ($media !== null) {
            $result = $media->builderUrl('LOGO', $include);

            $this->setProperty(
                'logo',
                $result['key'],
                $result['value']
            );
        }
    }

    /**
     * Add Raw Logo
     *
     * @param VCardMedia|null $media
     *
     * @throws ElementAlreadyExistsException
     */
    protected function addRawLogo(?VCardMedia $media): void
    {
        if ($media !== null) {
            $result = $media->builderRaw('LOGO');

            $this->setProperty(
                'logo',
                $result['key'],
                $result['value']
            );
        }
    }

    /**
     * Add Photo
     *
     * @param VCardMedia|null $media
     * @param bool            $include Include the image in our vcard?
     *
     * @throws ElementAlreadyExistsException
     * @throws EmptyUrlException
     * @throws InvalidImageException
     */
    protected function addPhoto(?VCardMedia $media, bool $include = true): void
    {
        if ($media !== null) {
            $result = $media->builderUrl('PHOTO', $include);

            $this->setProperty(
                'photo',
                $result['key'],
                $result['value']
            );
        }
    }

    /**
     * Add Raw Photo
     *
     * @param VCardMedia|null $media
     *
     * @throws ElementAlreadyExistsException
     */
    protected function addRawPhoto(?VCardMedia $media): void
    {
        if ($media !== null) {
            $result = $media->builderRaw('PHOTO');

            $this->setProperty(
                'photo',
                $result['key'],
                $result['value']
            );
        }
    }

    /**
     * Add URL
     *
     * @param null|string[][] $urls
     *
     * @throws ElementAlreadyExistsException
     */
    protected function addUrl($urls): void
    {
        if ($urls !== null) {
            foreach ($urls as $type => $sub) {
                foreach ($sub as $url) {
                    $this->setProperty(
                        'url',
                        'URL'.(($type !== '') ? ';'.$type : ''),
                        $url
                    );
                }
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
    protected function setProperty(string $element, string $key, string $value): void
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
