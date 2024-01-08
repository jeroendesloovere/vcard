<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard;

use Behat\Transliterator\Transliterator;
use DateTimeImmutable;
use finfo;
use InvalidArgumentException;
use Webmozart\Assert\Assert;
use const CURLOPT_RETURNTRANSFER;
use const CURLOPT_URL;
use const DIRECTORY_SEPARATOR;
use const FILEINFO_MIME_TYPE;
use const FILTER_VALIDATE_URL;
use const PREG_SPLIT_NO_EMPTY;

class VCard implements VCardInterface
{
    private const FILE_EXT_ICS = 'ics';

    private const FILE_EXT_VCF = 'vcf';

    private const PROPERTY_MULTI_WHITELIST = [
        'email',
        'address',
        'phoneNumber',
        'url',
        'label',
    ];

    private array $definedElements = [];

    private ?string $filename = null;

    private ?string $savePath = null;

    private array $properties;

    public string $charset = 'utf-8';

    public function addAddress(
        string $name = '',
        string $extended = '',
        string $street = '',
        string $city = '',
        string $region = '',
        string $zip = '',
        string $country = '',
        array $type = ['WORK', 'POSTAL'],
    ): void {
        Assert::allInArray($type, ['PERSONAL', 'DOM', 'INTL', 'POSTAL', 'PARCEL', 'HOME', 'WORK']);
        $this->setProperty(
            'address',
            sprintf(
                'ADR%s%s',
                $type !== [] ? ';' . implode(';', $type) : '',
                $this->getCharsetString(),
            ),
            sprintf(
                '%s;%s;%s;%s;%s;%s;%s',
                $name,
                $extended,
                $street,
                $city,
                $region,
                $zip,
                $country,
            ),
        );
    }

    public function addBirthday(DateTimeImmutable $date): void
    {
        $this->setProperty(
            'birthday',
            'BDAY',
            $date->format('Y-m-d'),
        );
    }

    public function addCompany(string $company, string $department = ''): void
    {
        $this->setProperty(
            'company',
            'ORG' . $this->getCharsetString(),
            $company
            . ($department !== '' ? ';' . $department : ''),
        );

        if ($this->filename === null) {
            $this->setFilename($company);
        }
    }

    public function addEmail(string $address, array $type = []): void
    {
        Assert::allInArray($type, ['PREF', 'WORK', 'HOME']);

        $this->setProperty(
            'email',
            'EMAIL;INTERNET' . (($type !== []) ? ';' . implode(';', $type) : ''),
            $address,
        );
    }

    public function addJobtitle(string $jobtitle): void
    {
        $this->setProperty(
            'jobtitle',
            'TITLE' . $this->getCharsetString(),
            $jobtitle,
        );
    }

    public function addLabel(string $label, string $type = ''): void
    {
        $this->setProperty(
            'label',
            'LABEL' . ($type === '' ? '' : ';' . $type) . $this->getCharsetString(),
            $label,
        );
    }

    public function addRole(string $role): void
    {
        $this->setProperty(
            'role',
            'ROLE' . $this->getCharsetString(),
            $role,
        );
    }

    private function addMedia(
        string $property,
        string $url,
        string $element,
        bool $include = true,
    ): void {
        $mimeType = null;

        if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
            $headers = get_headers($url, true);
            if ($headers !== false && array_key_exists('Content-Type', $headers)) {
                $mimeType = $headers['Content-Type'];
                if (is_array($mimeType)) {
                    $mimeType = end($mimeType);
                }
            }
        } else {
            $mimeType = mime_content_type($url);
        }

        if (str_contains($mimeType, ';')) {
            $mimeType = strstr($mimeType, ';', true);
        }

        if (!is_string($mimeType) || !str_starts_with($mimeType, 'image/')) {
            throw VCardException::invalidImage();
        }

        $fileType = strtoupper(substr($mimeType, 6));

        if ($include) {
            if ((bool) ini_get('allow_url_fopen') === true) {
                $value = file_get_contents($url);
            } else {
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                $value = curl_exec($curl);
                curl_close($curl);
            }

            if (!$value) {
                throw VCardException::emptyURL();
            }

            Assert::string($value);
            $value = base64_encode($value);
            $property .= ';ENCODING=b;TYPE=' . $fileType;
        } else {
            if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
                $propertySuffix = ';VALUE=URL';
                $propertySuffix .= ';TYPE=' . strtoupper($fileType);

                $property = $property . $propertySuffix;
            }
            $value = $url;
        }

        $this->setProperty(
            $element,
            $property,
            $value,
        );
    }

    private function addMediaContent(
        string $property,
        string $content,
        string $element,
    ): void {
        $finfo = new finfo();
        $mimeType = $finfo->buffer($content, FILEINFO_MIME_TYPE);

        Assert::string($mimeType);
        if (str_contains($mimeType, ';') === true) {
            $mimeType = strstr($mimeType, ';', true);
            Assert::string($mimeType);
        }

        if (str_starts_with($mimeType, 'image/') === false) {
            throw VCardException::invalidImage();
        }

        $fileType = strtoupper(substr($mimeType, 6));

        $content = base64_encode($content);
        $property .= ';ENCODING=b;TYPE=' . $fileType;

        $this->setProperty(
            $element,
            $property,
            $content,
        );
    }

    public function addName(
        string $lastName = '',
        string $firstName = '',
        string $additional = '',
        string $prefix = '',
        string $suffix = '',
    ): void {
        $values = array_filter([
            $prefix,
            $firstName,
            $additional,
            $lastName,
            $suffix,
        ]);
        $this->setFilename($values);

        $this->setProperty(
            'name',
            'N' . $this->getCharsetString(),
            sprintf('%s;%s;%s;%s;%s', $lastName, $firstName, $additional, $prefix, $suffix),
        );

        if ($this->hasProperty('FN') === false) {
            $this->setProperty(
                'fullname',
                'FN' . $this->getCharsetString(),
                trim(implode(' ', $values)),
            );
        }
    }

    public function addNote(string $note): void
    {
        $this->setProperty(
            'note',
            'NOTE' . $this->getCharsetString(),
            $note,
        );
    }

    public function addCategories(array $categories): void
    {
        $this->setProperty(
            'categories',
            'CATEGORIES' . $this->getCharsetString(),
            trim(implode(',', $categories)),
        );
    }

    public function addPhoneNumber(string $number, array $type = []): void
    {
        Assert::allInArray(
            $type,
            ['PREF', 'WORK', 'HOME', 'VOICE', 'FAX', 'MSG', 'CELL', 'PAGER', 'BBS', 'CAR', 'MODEM', 'ISDN', 'VIDEO'],
        );

        $this->setProperty(
            'phoneNumber',
            'TEL' . (($type != '') ? ';' . implode(';', $type) : ''),
            $number,
        );
    }

    public function addLogo(string $url, bool $include = true): void
    {
        $this->addMedia(
            'LOGO',
            $url,
            'logo',
            $include,
        );
    }

    public function addLogoContent(string $content): void
    {
        $this->addMediaContent(
            'LOGO',
            $content,
            'logo',
        );
    }

    public function addPhoto(string $url, bool $include = true): void
    {
        $this->addMedia(
            'PHOTO',
            $url,
            'photo',
            $include,
        );
    }

    public function addPhotoContent(string $content): void
    {
        $this->addMediaContent(
            'PHOTO',
            $content,
            'photo',
        );
    }

    public function addURL(string $url, string $type = ''): void
    {
        $this->setProperty(
            'url',
            'URL' . (($type != '') ? ';' . $type : ''),
            $url,
        );
    }

    public function buildVCard(): string
    {
        $string = VCardInterface::VCARD_START . \PHP_EOL;
        $string .= sprintf('%s:%s%s', self::VCARD_VERSION, self::VCARD_VERSION_VALUE, \PHP_EOL);
        $string .= sprintf('%s%sT%sZ%s', self::VCARD_REVISION, date('Y-m-d'), date('H:i:s'), \PHP_EOL);

        $properties = $this->getProperties();
        foreach ($properties as $property) {
            $string .= $this->fold($property['key'] . ':' . $this->escape($property['value'])) . "\r\n";
        }

        $string .= self::VCARD_END . \PHP_EOL;

        return $string;
    }

    public function buildVCalendar(): string
    {
        // init dates
        $start = date('Ymd') . 'T' . date('Hi') . '00';
        $end = date('Ymd') . 'T' . date('Hi') . '01';

        // init string
        $string = "BEGIN:VCALENDAR\n";
        $string .= "VERSION:2.0\n";
        $string .= "BEGIN:VEVENT\n";
        $string .= 'DTSTART;TZID=Europe/London:' . $start . "\n";
        $string .= 'DTEND;TZID=Europe/London:' . $end . "\n";
        $string .= "SUMMARY:Click attached contact below to save to your contacts\n";
        $string .= 'DTSTAMP:' . $start . "Z\n";
        $string .= "ATTACH;VALUE=BINARY;ENCODING=BASE64;FMTTYPE=text/directory;\n";
        $string .= ' X-APPLE-FILENAME=' . $this->getFilename() . '.' . $this->getFileExtension() . ":\n";

        $b64vcard = base64_encode($this->buildVCard());
        $b64mline = chunk_split($b64vcard, 74);
        $b64final = preg_replace('/(.+)/', ' $1', $b64mline);
        $string .= $b64final;
        $string .= "END:VEVENT\n";
        $string .= "END:VCALENDAR\n";

        return $string;
    }

    protected function getUserAgent(): string
    {
        return array_key_exists('HTTP_USER_AGENT', $_SERVER)
            ? strtolower($_SERVER['HTTP_USER_AGENT'])
            : 'unknown';
    }

    private function decode(string $value): string
    {
        return Transliterator::transliterate($value);
    }

    public function download(): string
    {
        foreach ($this->getHeaders(false) as $header) {
            header($header);
        }

        return $this->getOutput();
    }

    /**
     * @see https://github.com/jeroendesloovere/vcard/issues/153
     */
    protected function fold(string $text): string
    {
        return match (true) {
            strlen($text) <= 75 => $text,
            $this->isAscii($text) => substr(chunk_split($text, 75, "\r\n "), 0, -3),
            default => substr($this->chunkSplitUnicode($text, 75, "\r\n "), 0, -3),
        };
    }

    protected function isAscii(string $string = ''): bool
    {
        $num = 0;
        while (isset($string[$num])) {
            if (ord($string[$num]) & 0x80) {
                return false;
            }
            ++$num;
        }

        return true;
    }

    protected function chunkSplitUnicode(string $body, int $chunkLen = 76, string $end = "\r\n"): string
    {
        $parts = preg_split('//u', $body, -1, PREG_SPLIT_NO_EMPTY);
        Assert::isArray($parts);
        $array = array_chunk($parts, max(1, $chunkLen));
        $body = '';

        foreach ($array as $item) {
            $body .= implode('', $item) . $end;
        }

        return $body;
    }

    protected function escape(string $text): string
    {
        return str_replace(["\n", "\r\n"], '\\n', $text);
    }

    public function get(): string
    {
        return $this->getOutput();
    }

    public function getCharset(): string
    {
        return $this->charset;
    }

    public function getCharsetString(): string
    {
        return ';CHARSET=' . $this->charset;
    }

    public function getContentType(): string
    {
        return $this->isIOS7()
            ? 'text/x-vcalendar'
            : 'text/x-vcard';
    }

    public function getFilename(): string
    {
        if (!$this->filename) {
            return 'unknown';
        }

        return $this->filename;
    }

    public function getFileExtension(): string
    {
        return $this->isIOS7()
            ? self::FILE_EXT_ICS
            : self::FILE_EXT_VCF;
    }

    public function getHeaders(bool $asAssociative): array
    {
        $contentType = $this->getContentType() . '; charset=' . $this->getCharset();
        $contentDisposition = 'attachment; filename=' . $this->getFilename() . '.' . $this->getFileExtension();
        $contentLength = mb_strlen($this->getOutput(), '8bit');
        $connection = 'close';

        return $asAssociative ? [
            'Content-type' => $contentType,
            'Content-Disposition' => $contentDisposition,
            'Content-Length' => $contentLength,
            'Connection' => $connection,
        ] : [
            'Content-type: ' . $contentType,
            'Content-Disposition: ' . $contentDisposition,
            'Content-Length: ' . $contentLength,
            'Connection: ' . $connection,
        ];
    }

    public function getOutput(): string
    {
        return $this->isIOS7()
            ? $this->buildVCalendar()
            : $this->buildVCard();
    }

    public function getProperties(): array
    {
        return $this->properties;
    }

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

    public function isIOS(): bool
    {
        $browser = $this->getUserAgent();

        return strpos($browser, 'iphone') || strpos($browser, 'ipod') || strpos($browser, 'ipad');
    }

    public function isIOS7(): bool
    {
        return $this->isIOS() && $this->shouldAttachmentBeCal();
    }

    public function save(): void
    {
        $file = $this->getFilename() . '.' . $this->getFileExtension();

        if ($this->savePath !== null) {
            $file = $this->savePath . $file;
        }

        file_put_contents(
            $file,
            $this->getOutput(),
        );
    }

    public function setCharset(string $charset): void
    {
        $this->charset = $charset;
    }

    public function setFilename(
        string|array $value,
        bool $overwrite = true,
        string $separator = '_',
    ): void {
        if (is_array($value)) {
            $value = implode($separator, $value);
        }

        $value = trim($value, $separator);

        $value = preg_replace('/\s+/', $separator, $value);

        if (empty($value)) {
            return;
        }

        $value = strtolower($this->decode($value));
        $value = Transliterator::urlize($value);
        $this->filename = $overwrite
            ? $value
            : $this->filename . $separator . $value;
    }

    public function setSavePath(string $savePath): void
    {
        if (!is_dir($savePath)) {
            throw VCardException::outputDirectoryNotExists();
        }

        // Add trailing directory separator the save path
        if (substr($savePath, -1) != DIRECTORY_SEPARATOR) {
            $savePath .= DIRECTORY_SEPARATOR;
        }

        $this->savePath = $savePath;
    }

    private function setProperty(string $element, string $key, string $value): void
    {
        if (in_array($element, self::PROPERTY_MULTI_WHITELIST) === false && array_key_exists($element, $this->definedElements)) {
            throw new InvalidArgumentException(sprintf('You can only add one %s property', $element));
        }

        $this->definedElements[$element] = true;
        $this->properties[] = [
            'key' => $key,
            'value' => $value,
        ];
    }

    protected function shouldAttachmentBeCal(): bool
    {
        $browser = $this->getUserAgent();

        $matches = [];
        preg_match('/os (\d+)_(\d+)\s+/', $browser, $matches);
        $version = isset($matches[1]) ? ((int) $matches[1]) : 999;

        return $version < 8;
    }
}
