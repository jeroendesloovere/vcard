<?php

namespace JeroenDesloovere\VCard;

/**
 * VCard PHP Class to generate .vcard files and save them to a file or output as a download.
 *
 * @author Jeroen Desloovere <info@jeroendesloovere.be>
 */
class VCard
{
    /**
     * Filename
     *
     * @var string
     */
    private $filename;

    /**
     * Properties
     *
     * @var array
     */
    private $properties;

    /**
     * Add address
     *
     * @return void
     * @param  string[optional] $name
     * @param  string[optional] $extended
     * @param  string[optional] $street
     * @param  string[optional] $city
     * @param  string[optional] $region
     * @param  string[optional] $zip
     * @param  string[optional] $country
     * @param  string[optional] $type     $type may be DOM | INTL | POSTAL | PARCEL | HOME | WORK or any combination of these: e.g. "WORK;PARCEL;POSTAL"
     */
    public function addAddress(
        $name = '',
        $extended = '',
        $street = '',
        $city = '',
        $region = '',
        $zip = '',
        $country = '',
        $type = 'WORK;POSTAL'
    ) {
        // init value
        $value = $name . ';' . $extended . ';' . $street . ';' . $city . ';' . $region . ';' . $zip . ';' . $country;

        // set property
        $this->setProperty('ADR' . (($type != '') ? ';' . $type : ''), $value);
    }

    /**
     * Add birthday
     *
     * @return void
     * @param  string $date Format is YYYY-MM-DD
     */
    public function addBirthday($date)
    {
        $this->setProperty('BDAY', $date);
    }

    /**
     * Add company
     *
     * @return void
     * @param  string $company
     */
    public function addCompany($company)
    {
        $this->setProperty('ORG', $company);

        // if filename is empty, add to filename
        if (empty($this->filename)) $this->setFilename($company);
    }

    /**
     * Add email
     *
     * @return void
     * @param  string $address The e-mailaddress
     */
    public function addEmail($address)
    {
        $this->setProperty('EMAIL;INTERNET', $address);
    }

    /**
     * Add jobtitle
     *
     * @return void
     * @param  string $jobtitle The jobtitle for the person.
     */
    public function addJobtitle($jobtitle)
    {
        $this->setProperty('TITLE', $jobtitle);
    }

    /**
     * Add a photo or logo (depending on property name)
     *
     * @return void
     * @param  string $property LOGO|PHOTO
     * @param  string $url image url or filename
     * @param  bool   $encode to integrate / encode or not the file
     */
    private function addMedia($property, $url, $encode = false)
    {
        if ($encode) {
            $value = file_get_contents($url);

            // todo better MIME detection
            $mime = mime_content_type($url);
            $value = base64_encode($value);
            $property .= ";ENCODING=b;TYPE=" . strtoupper(str_replace('image/', '', $mime));
        } else {
            $value = $url;
        }
        
        $this->setProperty($property, $value);
    }

    /**
     * Add name
     *
     * @return void
     * @param  string[optional] $lastName
     * @param  string[optional] $firstName
     * @param  string[optional] $additional
     * @param  string[optional] $prefix
     * @param  string[optional] $suffix
     */
    public function addName(
        $lastName = '',
        $firstName = '',
        $additional = '',
        $prefix = '',
        $suffix = ''
    ) {
        // define values with non-empty values
        $values = array_filter(array(
            $prefix,
            $firstName,
            $additional,
            $lastName,
            $suffix
        ));

        // define filename
        $this->setFilename($values);

        // set property
        $this->setProperty('N', $lastName . ';' . $firstName . ';' . $additional . ';' . $prefix . ';' . $suffix);

        // is property FN set?
        if (!isset($this->properties['FN']) || $this->properties['FN'] == '') {
            // set property
            $this->setProperty('FN', trim(implode(' ', $values)));
        }
    }

    /**
     * Add note
     *
     * @return void
     * @param  string $note
     */
    public function addNote($note)
    {
        $this->setProperty('NOTE', $note);
    }

    /**
     * Add phone number
     *
     * @return void
     * @param  string           $number
     * @param  string[optional] $type   Type may be PREF | WORK | HOME | VOICE | FAX | MSG | CELL | PAGER | BBS | CAR | MODEM | ISDN | VIDEO or any senseful combination, e.g. "PREF;WORK;VOICE"
     */
    public function addPhoneNumber($number, $type = '')
    {
        $this->setProperty('TEL' . (($type != '') ? ';' . $type : ''), $number);
    }

    /**
     * Add Photo
     *
     * @return void
     * @param  string $url image url or filename
     * @param  bool   $encode to integrate / encode or not the file
     */
    public function addPhoto($url, $encode = false)
    {
        $this->addMedia('PHOTO', $url, $encode);
    }

    /**
     * Add URL
     *
     * @return void
     * @param  string           $url
     * @param  string[optional] $type Type may be WORK | HOME
     */
    public function addURL($url, $type = '')
    {
        $this->setProperty('URL' . (($type != '') ? ';' . $type : ''), $url);
    }

    /**
     * Build VCard (.vcf)
     *
     * @return string
     */
    public function buildVCard()
    {
        // init string
        $string = "BEGIN:VCARD\r\n";
        $string .= "VERSION:3.0\r\n";
        $string .= "REV:" . date("Y-m-d") . "T" . date("H:i:s") . "Z\r\n";

        // loop all properties
        foreach ($this->properties as $key => $value) {
            // add to string
            $string .= $key . ':' . $value . "\r\n";
        }

        // add to string
        $string .= "END:VCARD\r\n";

        // return
        return $string;
    }

    /**
     * Build VCalender (.ics) - Safari (iOS) can not open .vcf files, so we have build a workaround.
     *
     * @return string
     */
    public function buildVCalendar()
    {
        // init dates
        $dtstart = date("Ymd") . "T" . date("Hi") . "00";
        $dtend = date("Ymd") . "T" . date("Hi") . "01";

        // init string
        $string = "BEGIN:VCALENDAR\n";
        $string .= "VERSION:2.0\n";
        $string .= "BEGIN:VEVENT\n";
        $string .= "DTSTART;TZID=Europe/London:" . $dtstart . "\n";
        $string .= "DTEND;TZID=Europe/London:" . $dtend . "\n";
        $string .= "SUMMARY:Click attached contact below to save to your contacts\n";
        $string .= "DTSTAMP:" . $dtstart . "Z\n";
        $string .= "ATTACH;VALUE=BINARY;ENCODING=BASE64;FMTTYPE=text/directory;\n";
        $string .= " X-APPLE-FILENAME=" . $this->filename . ".vcf:\n";

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
     * Decode
     *
     * @return string decoded
     * @param  string $value The value to decode
     */
    private function decode($value)
    {
        return htmlspecialchars_decode((string) $value, ENT_QUOTES);
    }

    /**
     * Download
     *
     * @return header will push file to your browser
     */
    public function download()
    {
        // iOS devices
        if ($this->isIOS()) {
            // define output
            $output = $this->buildVCalendar();

            // send correct headers
            header('Content-type: text/x-vcalendar; charset=utf-8');
            header('Content-Disposition: attachment; filename=' . $this->filename . '.ics;');
        // non-iOS devices
        } else {
            // define output
            $output = $this->buildVCard();

            // send correct headers
            header('Content-type: text/x-vcard; charset=UTF-8');
            header('Content-Disposition: attachment; filename=' . $this->filename . '.vcf;');
        }

        // send correct headers
        header('Content-Length: ' . strlen($output));
        header('Connection: close');

        // echo the output and it will be a download
        echo $output;
    }

    /**
     * Get output as string
     *
     * @return string
     */
    public function get()
    {
        // return output for iOS devices or for non-iOS devices
        return ($this->isIOS()) ? $this->buildVCalendar() : $this->buildVCard();
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Is iOS - Check if the user is using an iOS-device
     *
     * @return bool
     */
    public function isIOS()
    {
        // get user agent
        $browser = strtolower($_SERVER['HTTP_USER_AGENT']);

        // return bool
        return (strpos($browser, 'iphone') || strpos($browser, 'ipod') || strpos($browser, 'ipad')) ? true : false;
    }

    /**
     * Save to a file
     *
     * @return void
     */
    public function save()
    {
        // iOS devices - save to file as .ics
        if ($this->isIOS()) file_put_contents($this->filename . '.ics', $this->buildVCalendar());

        // non-iOS devices - save to file as .vcf
        else file_put_contents($this->filename . '.vcf', $this->buildVCard());
    }

    /**
     * Set filename
     *
     * @return void
     * @param  mixed  $value
     * @param  bool   $overwrite[optional] Default overwrite is true
     * @param  string $separator[optional] Default separator is an underscore '_'
     */
    public function setFilename($value, $overwrite = true, $separator = '_')
    {
        // recast to string if $value is array
        if (is_array($value)) $value = implode($separator, $value);

        // trim unneeded values
        $value = trim($value, $separator);

        // remove all spaces
        $value = preg_replace('/\s+/', $separator, $value);

        // if value is empty, stop here
        if (empty($value)) return false;

        // decode value + lowercase the string
        $value = strtolower($this->decode($value));

        // overwrite filename or add to filename using a prefix in between
        $this->filename = ($overwrite) ? $value : $this->filename . $separator . $value;
    }

    /**
     * Set property
     *
     * @return void
     * @param  string $key
     * @param  string $value
     */
    private function setProperty($key, $value)
    {
        // set decoded property
        $this->properties[$key] = $this->decode($value);
    }
}

/**
 * VCard Exception class
 *
 * @author Jeroen Desloovere <info@jeroendesloovere.be>
 */
class VCardException extends \Exception {}
