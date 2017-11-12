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
use JeroenDesloovere\VCard\Exception\VCardException;

/**
 * VCard PHP Class to generate .vcard files and save them to a file or output as a download.
 */
class VCard
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
    private $savePath = null;

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
     * Add address
     *
     * @param string $name     [optional]
     * @param string $extended [optional]
     * @param string $street   [optional]
     * @param string $city     [optional]
     * @param string $region   [optional]
     * @param string $zip      [optional]
     * @param string $country  [optional]
     * @param string $type     [optional]
     *                         $type may be DOM | INTL | POSTAL | PARCEL | HOME | WORK
     *                         or any combination of these: e.g. "WORK;PARCEL;POSTAL"
     *
     * @return $this
     * @throws ElementAlreadyExistsException
     */
    public function addAddress(
        string $name = '',
        string $extended = '',
        string $street = '',
        string $city = '',
        string $region = '',
        string $zip = '',
        string $country = '',
        string $type = 'WORK;POSTAL'
    ) {
        // init value
        $value = $name.';'.$extended.';'.$street.';'.$city.';'.$region.';'.$zip.';'.$country;

        // set property
        $this->setProperty(
            'address',
            'ADR'.(($type !== '') ? ';'.$type : '').$this->getCharsetString(),
            $value
        );

        return $this;
    }

    /**
     * Add birthday
     *
     * @param string $date Format is YYYY-MM-DD
     *
     * @return $this
     * @throws ElementAlreadyExistsException
     */
    public function addBirthday(string $date)
    {
        $this->setProperty(
            'birthday',
            'BDAY',
            $date
        );

        return $this;
    }

    /**
     * Add company
     *
     * @param string $company
     * @param string $department
     *
     * @return $this
     * @throws ElementAlreadyExistsException
     */
    public function addCompany(string $company, string $department = '')
    {
        $this->setProperty(
            'company',
            'ORG'.$this->getCharsetString(),
            $company.($department !== '' ? ';'.$department : '')
        );

        // if filename is empty, add to filename
        if ($this->filename === null) {
            $this->setFilename($company);
        }

        return $this;
    }

    /**
     * Add email
     *
     * @param string $address The e-mail address
     * @param string $type    [optional]
     *                        The type of the email address
     *                        $type may be  PREF | WORK | HOME
     *                        or any combination of these: e.g. "PREF;WORK"
     *
     * @return $this
     * @throws ElementAlreadyExistsException
     */
    public function addEmail(string $address, string $type = '')
    {
        $this->setProperty(
            'email',
            'EMAIL;INTERNET'.(($type != '') ? ';'.$type : ''),
            $address
        );

        return $this;
    }

    /**
     * Add jobtitle
     *
     * @param string $jobtitle The jobtitle for the person.
     *
     * @return $this
     * @throws ElementAlreadyExistsException
     */
    public function addJobtitle(string $jobtitle)
    {
        $this->setProperty(
            'jobtitle',
            'TITLE'.$this->getCharsetString(),
            $jobtitle
        );

        return $this;
    }

    /**
     * Add role
     *
     * @param string $role The role for the person.
     *
     * @return $this
     * @throws ElementAlreadyExistsException
     */
    public function addRole(string $role)
    {
        $this->setProperty(
            'role',
            'ROLE'.$this->getCharsetString(),
            $role
        );

        return $this;
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
     * @return $this
     * @throws ElementAlreadyExistsException
     */
    public function addName(
        string $lastName = '',
        string $firstName = '',
        string $additional = '',
        string $prefix = '',
        string $suffix = ''
    ) {
        // define values with non-empty values
        $values = array_filter([
            $prefix,
            $firstName,
            $additional,
            $lastName,
            $suffix,
        ]);

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
        if (!$this->hasProperty('FN')) {
            // set property
            $this->setProperty(
                'fullname',
                'FN'.$this->getCharsetString(),
                trim(implode(' ', $values))
            );
        }

        return $this;
    }

    /**
     * Add note
     *
     * @param string $note
     *
     * @return $this
     * @throws ElementAlreadyExistsException
     */
    public function addNote(string $note)
    {
        $this->setProperty(
            'note',
            'NOTE'.$this->getCharsetString(),
            $note
        );

        return $this;
    }

    /**
     * Add categories
     *
     * @param array $categories
     *
     * @return $this
     * @throws ElementAlreadyExistsException
     */
    public function addCategories(array $categories)
    {
        $this->setProperty(
            'categories',
            'CATEGORIES'.$this->getCharsetString(),
            trim(implode(',', $categories))
        );

        return $this;
    }

    /**
     * Add phone number
     *
     * @param string $number
     * @param string $type   [optional]
     *                       Type may be PREF | WORK | HOME | VOICE | FAX | MSG |
     *                       CELL | PAGER | BBS | CAR | MODEM | ISDN | VIDEO
     *                       or any senseful combination, e.g. "PREF;WORK;VOICE"
     *
     * @return $this
     * @throws ElementAlreadyExistsException
     */
    public function addPhoneNumber(string $number, string $type = '')
    {
        $this->setProperty(
            'phoneNumber',
            'TEL'.(($type != '') ? ';'.$type : ''),
            $number
        );

        return $this;
    }

    /**
     * Add Logo
     *
     * @param string $url     image url or filename
     * @param bool   $include Include the image in our vcard?
     *
     * @return $this
     * @throws ElementAlreadyExistsException
     * @throws EmptyUrlException
     * @throws InvalidImageException
     */
    public function addLogo(string $url, bool $include = true)
    {
        $this->addMedia(
            'LOGO',
            $url,
            'logo',
            $include
        );

        return $this;
    }

    /**
     * Add Photo
     *
     * @param string $url     image url or filename
     * @param bool   $include Include the image in our vcard?
     *
     * @return $this
     * @throws ElementAlreadyExistsException
     * @throws EmptyUrlException
     * @throws InvalidImageException
     */
    public function addPhoto(string $url, bool $include = true)
    {
        $this->addMedia(
            'PHOTO',
            $url,
            'photo',
            $include
        );

        return $this;
    }

    /**
     * Add URL
     *
     * @param string $url
     * @param string $type [optional] Type may be WORK | HOME
     *
     * @return $this
     * @throws ElementAlreadyExistsException
     */
    public function addURL(string $url, string $type = '')
    {
        $this->setProperty(
            'url',
            'URL'.(($type !== '') ? ';'.$type : ''),
            $url
        );

        return $this;
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
            $string .= $this->fold($property['key'].':'.$this->escape($property['value'])."\r\n");
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
    public function download()
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
     * Get output as string
     * @deprecated in the future
     *
     * @return string
     */
    public function get(): string
    {
        return $this->getOutput();
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
        return $this->isIOS7() ?
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
        return $this->isIOS7() ?
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
        $output = $this->isIOS7() ?
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
     * Is iOS - Check if the user is using an iOS-device
     *
     * @return bool
     */
    public function isIOS(): bool
    {
        // get user agent
        $browser = $this->getUserAgent();

        return (strpos($browser, 'iphone') || strpos($browser, 'ipod') || strpos($browser, 'ipad'));
    }

    /**
     * Is iOS less than 7 (should cal wrapper be returned)
     *
     * @return bool
     */
    public function isIOS7(): bool
    {
        return ($this->isIOS() && $this->shouldAttachmentBeCal());
    }

    /**
     * Save to a file
     *
     * @return void
     */
    public function save()
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
    public function setCharset(string $charset)
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
    public function setFilename($value, $overwrite = true, $separator = '_')
    {
        // recast to string if $value is array
        if (is_array($value)) {
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

        // decode value + lowercase the string
        $value = strtolower($this->decode($value));

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
    public function setSavePath($savePath)
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
     * Checks if we should return vcard in cal wrapper
     *
     * @return bool
     */
    protected function shouldAttachmentBeCal(): bool
    {
        $browser = $this->getUserAgent();

        $matches = [];
        preg_match('/os (\d+)_(\d+)\s+/', $browser, $matches);
        $version = isset($matches[1]) ? ((int) $matches[1]) : 999;

        return ($version < 8);
    }

    /**
     * Fold a line according to RFC2425 section 5.8.1.
     *
     * @link http://tools.ietf.org/html/rfc2425#section-5.8.1
     *
     * @param string $text
     *
     * @return bool|string
     */
    protected function fold($text)
    {
        if (strlen($text) <= 75) {
            return $text;
        }

        // split, wrap and trim trailing separator
        return substr(chunk_split($text, 73, "\r\n "), 0, -3);
    }

    /**
     * Escape newline characters according to RFC2425 section 5.8.4.
     *
     * @link http://tools.ietf.org/html/rfc2425#section-5.8.4
     *
     * @param string $text
     *
     * @return string
     */
    protected function escape(string $text): string
    {
        $text = str_replace(array("\r\n", "\n"), "\\n", $text);

        return $text;
    }

    /**
     * Returns the browser user agent string.
     *
     * @return string
     */
    protected function getUserAgent(): string
    {
        $browser = 'unknown';

        if (array_key_exists('HTTP_USER_AGENT', $_SERVER)) {
            $browser = strtolower($_SERVER['HTTP_USER_AGENT']);
        }

        return $browser;
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
    private function setProperty(string $element, string $key, string $value)
    {
        if (isset($this->definedElements[$element])
            && !in_array($element, $this::$multiplePropertiesForElementAllowed, true)) {
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

    /**
     * Decode
     *
     * @param string $value The value to decode
     *
     * @return string decoded
     */
    private function decode(string $value): string
    {
        // convert cyrlic, greek or other caracters to ASCII characters
        return Transliterator::transliterate($value);
    }

    /**
     * Add a photo or logo (depending on property name)
     *
     * @param string $property LOGO|PHOTO
     * @param string $url      image url or filename
     * @param string $element  The name of the element to set
     * @param bool   $include  Do we include the image in our vcard or not?
     *
     * @throws ElementAlreadyExistsException
     * @throws EmptyUrlException
     * @throws InvalidImageException
     */
    private function addMedia(string $property, string $url, string $element, bool $include = true)
    {
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
        if (!is_string($mimeType) || 0 !== strpos($mimeType, 'image/')) {
            throw new InvalidImageException();
        }
        $fileType = strtoupper(substr($mimeType, 6));

        if ($include) {
            $value = file_get_contents($url);

            if (!$value) {
                throw new EmptyUrlException();
            }

            $value = base64_encode($value);
            $property .= ';ENCODING=b;TYPE='.$fileType;
        } else {
            if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
                $propertySuffix = ';VALUE=URL';
                $propertySuffix .= ';TYPE='.strtoupper($fileType);

                $property .= $propertySuffix;
                $value = $url;
            } else {
                $value = $url;
            }
        }

        $this->setProperty(
            $element,
            $property,
            $value
        );
    }
}
