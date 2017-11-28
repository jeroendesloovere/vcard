<?php

namespace JeroenDesloovere\VCard\Tests;

/*
 * This file is part of the VCard PHP Class from Jeroen Desloovere.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use JeroenDesloovere\VCard\Model\VCard;
use JeroenDesloovere\VCard\Model\VCardMedia;
use JeroenDesloovere\VCard\VCardBuilder;
use PHPUnit\Framework\TestCase;

/**
 * This class will test our VCard PHP Class which can generate VCards.
 */
class VCardBuilderTest extends TestCase
{
    /**
     * @var VCardBuilder
     */
    protected $builder;

    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var string
     */
    protected $additional;

    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var string
     */
    protected $suffix;

    /**
     * @var string
     */
    protected $emailAddress1;

    /**
     * @var string
     */
    protected $emailAddress2;

    /**
     * @var string
     */
    protected $firstName2;

    /**
     * @var string
     */
    protected $lastName2;

    /**
     * @var string
     */
    protected $firstName3;

    /**
     * @var string
     */
    protected $lastName3;

    /**
     * Data provider for testEmail()
     *
     * @return array
     */
    public function emailDataProvider(): array
    {
        return [
            [['john@doe.com']],
            [['john@doe.com', 'WORK' => 'john@work.com']],
            [['WORK' => 'john@work.com', 'HOME' => 'john@home.com']],
            [['PREF;WORK' => 'john@work.com', 'HOME' => 'john@home.com']],
        ];
    }

    /**
     * Set up before class
     *
     * @return void
     */
    public function setUp(): void
    {
        // set timezone
        date_default_timezone_set('Europe/Brussels');

        $vcard = new VCard();
        $this->builder = new VCardBuilder($vcard);

        $this->firstName = 'Jeroen';
        $this->lastName = 'Desloovere';
        $this->additional = '&';
        $this->prefix = 'Mister';
        $this->suffix = 'Junior';

        $this->emailAddress1 = '';
        $this->emailAddress2 = '';

        $this->firstName2 = 'Ali';
        $this->lastName2 = 'ÖZSÜT';

        $this->firstName3 = 'Garçon';
        $this->lastName3 = 'Jéroèn';
    }

    /**
     * Tear down after class
     */
    public function tearDown()
    {
        $this->builder = null;
    }

    /**
     *
     */
    public function testAddEmail()
    {
        $vcard = new VCard();
        $vcard->addEmail($this->emailAddress1);
        $vcard->addEmail($this->emailAddress2);
        $builder = new VCardBuilder($vcard);
        $this->assertCount(2, $builder->getProperties());
    }

    /**
     *
     */
    public function testAddPhoneNumber()
    {
        $vcard = new VCard();
        $vcard->addPhone('');
        $vcard->addPhone('');
        $builder = new VCardBuilder($vcard);
        $this->assertCount(2, $builder->getProperties());
    }

    /**
     *
     */
    public function testAddPhotoWithJpgPhoto()
    {
        $vcard = new VCard();
        $vcardMedia = new VCardMedia();
        $vcardMedia->addUrlMedia(__DIR__.'/image.jpg');
        $vcard->setPhoto($vcardMedia);
        $builder = new VCardBuilder($vcard);
        $this->assertCount(1, $builder->getProperties());
    }

    /**
     *
     */
    public function testAddPhotoWithRemoteJpgPhoto()
    {
        $vcard = new VCard();
        $vcardMedia = new VCardMedia();
        $vcardMedia->addUrlMedia('https://raw.githubusercontent.com/jeroendesloovere/vcard/master/tests/image.jpg');
        $vcard->setPhoto($vcardMedia);
        $builder = new VCardBuilder($vcard);
        $this->assertCount(1, $builder->getProperties());
    }

    /**
     *
     */
    public function testAddLogoWithJpgImage()
    {
        $vcard = new VCard();
        $vcardMedia = new VCardMedia();
        $vcardMedia->addUrlMedia(__DIR__.'/image.jpg');
        $vcard->setLogo($vcardMedia);
        $builder = new VCardBuilder($vcard);
        $this->assertCount(1, $builder->getProperties());
    }

    /**
     *
     */
    public function testAddLogoWithJpgImageNoInclude()
    {
        $vcard = new VCard();
        $vcardMedia = new VCardMedia();
        $vcardMedia->addUrlMedia(__DIR__.'/image.jpg', false);
        $vcard->setLogo($vcardMedia);
        $builder = new VCardBuilder($vcard);
        $this->assertCount(1, $builder->getProperties());
    }

    /**
     *
     */
    public function testAddUrl()
    {
        $vcard = new VCard();
        $vcard->addUrl('1');
        $vcard->addUrl('2');
        $builder = new VCardBuilder($vcard);
        $this->assertCount(2, $builder->getProperties());
    }

    /**
     * Test charset
     */
    public function testCharset()
    {
        $charset = 'ISO-8859-1';
        $this->builder->setCharset($charset);
        $this->assertEquals($charset, $this->builder->getCharset());
    }

    /**
     * Test Email
     *
     * @dataProvider emailDataProvider $emails
     *
     * @param array $emails
     */
    public function testEmail(array $emails = [])
    {
        $vcard = new VCard();
        foreach ($emails as $key => $email) {
            if (\is_string($key)) {
                $vcard->addEmail($email, $key);
            } else {
                $vcard->addEmail($email);
            }
        }
        $builder = new VCardBuilder($vcard);

        $output = $builder->getOutput();

        foreach ($emails as $key => $email) {
            if (\is_string($key)) {
                $this->assertContains('EMAIL;INTERNET;'.$key.';CHARSET=utf-8:'.$email, $output);
            } else {
                $this->assertContains('EMAIL;INTERNET;CHARSET=utf-8:'.$email, $output);
            }
        }
    }

    /**
     * Test first name and last name
     */
    public function testFirstNameAndLastName()
    {
        $vcard = new VCard();
        $vcard->setFirstName($this->firstName);
        $vcard->setLastName($this->lastName);
        $builder = new VCardBuilder($vcard);

        $this->assertEquals('jeroen-desloovere', $builder->getFilename());
    }

    /**
     * Test full blown name
     */
    public function testFullBlownName()
    {
        $vcard = new VCard();
        $vcard->setPrefix($this->prefix);
        $vcard->setFirstName($this->firstName);
        $vcard->setAdditional($this->additional);
        $vcard->setLastName($this->lastName);
        $vcard->setSuffix($this->suffix);
        $builder = new VCardBuilder($vcard);

        $this->assertEquals('mister-jeroen-desloovere-junior', $builder->getFilename());
    }

    /**
     * Test special first name and last name
     */
    public function testSpecialFirstNameAndLastName()
    {
        $vcard = new VCard();
        $vcard->setFirstName($this->firstName2);
        $vcard->setLastName($this->lastName2);
        $builder = new VCardBuilder($vcard);

        $this->assertEquals('ali-ozsut', $builder->getFilename());
    }

    /**
     * Test special first name and last name
     */
    public function testSpecialFirstNameAndLastName2()
    {
        $vcard = new VCard();
        $vcard->setFirstName($this->firstName3);
        $vcard->setLastName($this->lastName3);
        $builder = new VCardBuilder($vcard);

        $this->assertEquals('garcon-jeroen', $builder->getFilename());
    }

    /**
     * Test hasProperty is true
     */
    public function testHasPropertyTrue()
    {
        $vcard = new VCard();
        $vcard->setFirstName($this->firstName);
        $vcard->setLastName($this->lastName);
        $builder = new VCardBuilder($vcard);

        $this->assertEquals(true, $builder->hasProperty('FN'.$builder->getCharsetString()));
    }

    /**
     * Test hasProperty is false
     */
    public function testHasPropertyFalse()
    {
        $vcard = new VCard();
        $builder = new VCardBuilder($vcard);

        $this->assertEquals(false, $builder->hasProperty('FN'.$builder->getCharsetString()));
    }

    /**
     * Test getFilename is unknown if setFilename is empty
     */
    public function testSetFilenameEmpty()
    {
        $vcard = new VCard();
        $builder = new VCardBuilder($vcard);
        $builder->setFilename('');

        $this->assertEquals('unknown', $builder->getFilename());
    }

    /**
     * Test hasProperty is false
     */
    public function testGetHeadersFalse()
    {
        $vcard = new VCard();
        $builder = new VCardBuilder($vcard);

        $this->assertEquals([
            'Content-type: text/x-vcard; charset=utf-8',
            'Content-Disposition: attachment; filename=unknown.vcf',
            'Content-Length: 63',
            'Connection: close',
        ], $builder->getHeaders(false));
    }

    /**
     * Test hasProperty is true
     */
    public function testGetHeadersTrue()
    {
        $vcard = new VCard();
        $builder = new VCardBuilder($vcard);

        $this->assertEquals([
            'Content-type' => 'text/x-vcard; charset=utf-8',
            'Content-Disposition' => 'attachment; filename=unknown.vcf',
            'Content-Length' => 63,
            'Connection' =>'close' ,
        ], $builder->getHeaders(true));
    }

    /**
     * Test VCalendar
     *
     * @runInSeparateProcess
     */
    public function testGetFileExtensionVcf()
    {
        $vcard = new VCard();
        $builder = new VCardBuilder($vcard);

        $this->assertEquals('vcf', $builder->getFileExtension());
    }

    /**
     * Test VCalendar
     *
     * @runInSeparateProcess
     */
    public function testGetFileExtensionIcs()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPad; U; CPU OS 3_2_2 like Mac OS X; nl-nl) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B500 Safari/531.21.10';

        $vcard = new VCard();
        $builder = new VCardBuilder($vcard);

        $this->assertEquals('ics', $builder->getFileExtension());
    }

    /**
     * Test VCalendar
     *
     * @runInSeparateProcess
     */
    public function testGetContentTypeVCard()
    {
        $vcard = new VCard();
        $builder = new VCardBuilder($vcard);

        $this->assertEquals('text/x-vcard', $builder->getContentType());
    }

    /**
     * Test VCalendar
     *
     * @runInSeparateProcess
     */
    public function testGetContentTypeVCalendar()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPad; U; CPU OS 3_2_2 like Mac OS X; nl-nl) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B500 Safari/531.21.10';

        $vcard = new VCard();
        $builder = new VCardBuilder($vcard);

        $this->assertEquals('text/x-vcalendar', $builder->getContentType());
    }

    /**
     * Test VCalendar
     *
     * @runInSeparateProcess
     */
    public function testVCalendar()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPad; U; CPU OS 3_2_2 like Mac OS X; nl-nl) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B500 Safari/531.21.10';

        $vcard = new VCard();
        $builder = new VCardBuilder($vcard);

        $output = $builder->getOutput();

        $this->assertContains('BEGIN:VCALENDAR', $output);
    }
}
