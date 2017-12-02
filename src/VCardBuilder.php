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
use JeroenDesloovere\VCard\Exception\OutputDirectoryNotExistsException;
use JeroenDesloovere\VCard\Model\VCard;
use JeroenDesloovere\VCard\Service\PropertyService;
use JeroenDesloovere\VCard\Util\GeneralUtil;
use JeroenDesloovere\VCard\Util\UserAgentUtil;

/**
 * VCard PHP Class to generate .vcard files and save them to a file or output as a download.
 */
class VCardBuilder
{
    /**
     * Filename
     *
     * @var string|null
     */
    private $filename;

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
    private $charset;

    /**
     * VCardBuilder constructor.
     *
     * @param VCard|VCard[] $vCard
     * @param string        $charset
     *
     * @throws ElementAlreadyExistsException
     */
    public function __construct($vCard, $charset = 'utf-8')
    {
        $this->charset = $charset;

        $propertyUtil = new PropertyService($vCard, $charset);

        $this->filename = $propertyUtil->getFilename();
        $this->properties = $propertyUtil->getProperties();
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
        $dtbase = date('Ymd').'T'.date('Hi');
        $dtstart = $dtbase.'00';
        $dtend = $dtbase.'01';

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

        // base64 encode it so that it can be used as an attachment to the "dummy" calendar appointment
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

        $pregQuoteSeparator = preg_quote($separator, '/');

        // if value is empty, stop here
        if (empty($value) || !preg_match("/[^\s$pregQuoteSeparator]/", $value)) {
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
     * Get filename
     *
     * @return string
     */
    public function getFilename(): string
    {
        if ($this->filename === null) {
            return 'unknown';
        }

        return $this->filename;
    }

    /**
     * Get content type
     *
     * @return string
     */
    public function getContentType(): string
    {
        return UserAgentUtil::isIOS7() ?
            'text/x-vcalendar' : 'text/x-vcard';
    }

    /**
     * Get file extension
     *
     * @return string
     */
    public function getFileExtension(): string
    {
        return UserAgentUtil::isIOS7() ?
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
        $output = UserAgentUtil::isIOS7() ?
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
     * @param string|null $savePath
     *
     * @return void
     * @throws OutputDirectoryNotExistsException
     */
    public function save(string $savePath = null): void
    {
        $file = $this->getFilename().'.'.$this->getFileExtension();

        // Add save path if given
        if (null !== $savePath) {
            $savePath = self::checkSavePath($savePath);

            $file = $savePath.$file;
        }

        file_put_contents(
            $file,
            $this->getOutput()
        );
    }

    /**
     * Check the save path directory
     *
     * @param string $savePath Save Path
     *
     * @return string
     * @throws OutputDirectoryNotExistsException
     */
    private static function checkSavePath($savePath): string
    {
        if (!is_dir($savePath)) {
            throw new OutputDirectoryNotExistsException();
        }

        // Add trailing directory separator the save path
        if (substr($savePath, -1) !== DIRECTORY_SEPARATOR) {
            $savePath .= DIRECTORY_SEPARATOR;
        }

        return $savePath;
    }
}
