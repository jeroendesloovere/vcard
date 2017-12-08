<?php

namespace JeroenDesloovere\VCard\Tests;

use JeroenDesloovere\VCard\Model\VCard;
use JeroenDesloovere\VCard\Model\VCardMedia;
use JeroenDesloovere\VCard\VCardBuilder;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamException;
use org\bovigo\vfs\vfsStreamWrapper;
use PHPUnit\Framework\TestCase;

/**
 * Class VCardBuilderTest
 *
 * @package JeroenDesloovere\VCard\Tests
 */
class VCardBuilderTest extends TestCase
{
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
     * @throws vfsStreamException
     */
    public function setUp(): void
    {
        // set timezone
        date_default_timezone_set('Europe/Brussels');

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

        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('exampleDir'));
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
     * @throws \JeroenDesloovere\VCard\Exception\EmptyUrlException
     * @throws \JeroenDesloovere\VCard\Exception\InvalidImageException
     * @throws \JeroenDesloovere\VCard\Exception\InvalidUrlException
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
     * @throws \JeroenDesloovere\VCard\Exception\EmptyUrlException
     * @throws \JeroenDesloovere\VCard\Exception\InvalidImageException
     * @throws \JeroenDesloovere\VCard\Exception\InvalidUrlException
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
     * @throws \JeroenDesloovere\VCard\Exception\EmptyUrlException
     * @throws \JeroenDesloovere\VCard\Exception\InvalidImageException
     * @throws \JeroenDesloovere\VCard\Exception\InvalidUrlException
     */
    public function testAddPhotoWithRemoteJpgPhotoNoInclude()
    {
        $vcard = new VCard();
        $vcardMedia = new VCardMedia();
        $vcardMedia->addUrlMedia('https://raw.githubusercontent.com/jeroendesloovere/vcard/master/tests/image.jpg', false);
        $vcard->setPhoto($vcardMedia);
        $builder = new VCardBuilder($vcard);
        $this->assertCount(1, $builder->getProperties());
    }

    /**
     * @throws \JeroenDesloovere\VCard\Exception\EmptyUrlException
     * @throws \JeroenDesloovere\VCard\Exception\InvalidImageException
     * @throws \JeroenDesloovere\VCard\Exception\InvalidUrlException
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
     * @throws \JeroenDesloovere\VCard\Exception\EmptyUrlException
     * @throws \JeroenDesloovere\VCard\Exception\InvalidImageException
     * @throws \JeroenDesloovere\VCard\Exception\InvalidUrlException
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
        $vcard = new VCard();
        $builder = new VCardBuilder($vcard, $charset);
        $this->assertEquals($charset, $builder->getCharset());
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
                $this->assertContains('EMAIL;INTERNET;'.$key.';CHARSET=UTF-8:'.$email, $output);
            } else {
                $this->assertContains('EMAIL;INTERNET;CHARSET=UTF-8:'.$email, $output);
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

        $this->assertEquals('jeroen-desloovere', $builder->getFileName());
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

        $this->assertEquals('mister-jeroen-desloovere-junior', $builder->getFileName());
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

        $this->assertEquals('ali-ozsut', $builder->getFileName());
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

        $this->assertEquals('garcon-jeroen', $builder->getFileName());
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

        $this->assertTrue($builder->hasProperty('FN'.$builder->getCharsetString()));
    }

    /**
     * Test hasProperty is false
     */
    public function testHasPropertyFalse()
    {
        $vcard = new VCard();
        $builder = new VCardBuilder($vcard);

        $this->assertFalse($builder->hasProperty('FN'.$builder->getCharsetString()));
    }

    /**
     * Test getFileName is unknown if setFileName is empty
     */
    public function testSetFileNameEmpty()
    {
        $vcard = new VCard();
        $builder = new VCardBuilder($vcard);
        $builder->setFileName('');

        $this->assertEquals('unknown', $builder->getFileName());
    }

    /**
     * Test getFileName is unknown if setFileName is a space
     */
    public function testSetFileNameSpace()
    {
        $vcard = new VCard();
        $builder = new VCardBuilder($vcard);
        $builder->setFileName(' ');

        $this->assertEquals('unknown', $builder->getFileName());
    }

    /**
     * Test getFileName is unknown if setFileName is array full of empty strings
     */
    public function testSetFileNameArrayEmpty()
    {
        $vcard = new VCard();
        $builder = new VCardBuilder($vcard);
        $builder->setFileName(['', '', '', '', '']);

        $this->assertEquals('unknown', $builder->getFileName());
    }

    /**
     * Test getFileName is unknown if setFileName is array full of strings with a space
     */
    public function testSetFileNameArraySpaces()
    {
        $vcard = new VCard();
        $builder = new VCardBuilder($vcard);
        $builder->setFileName([' ', ' ', ' ', ' ', ' ']);

        $this->assertEquals('unknown', $builder->getFileName());
    }

    /**
     * Test getFileName is unknown if setFileName is empty
     */
    public function testSetFileNameArray()
    {
        $vcard = new VCard();
        $builder = new VCardBuilder($vcard);
        $builder->setFileName(['Test', 'FileName', 'Array']);

        $this->assertEquals('test-filename-array', $builder->getFileName());
    }

    /**
     * Test getFileName is unknown if setFileName is a space
     */
    public function testSetFileNameSpaceSeparatorSlash()
    {
        $vcard = new VCard();
        $builder = new VCardBuilder($vcard);
        $builder->setFileName(' ', true, '/');

        $this->assertEquals('unknown', $builder->getFileName());
    }

    /**
     * Test getFileName is unknown if setFileName is array full of empty strings
     */
    public function testSetFileNameArrayEmptySeparatorSlash()
    {
        $vcard = new VCard();
        $builder = new VCardBuilder($vcard);
        $builder->setFileName(['', '', '', '', ''], true, '/');

        $this->assertEquals('unknown', $builder->getFileName());
    }

    /**
     * Test getFileName is unknown if setFileName is array full of strings with a space
     */
    public function testSetFileNameArraySpacesSeparatorSlash()
    {
        $vcard = new VCard();
        $builder = new VCardBuilder($vcard);
        $builder->setFileName([' ', ' ', ' ', ' ', ' '], true, '/');

        $this->assertEquals('unknown', $builder->getFileName());
    }

    /**
     * Test getFileName is unknown if setFileName is empty
     */
    public function testSetFileNameArraySeparatorSlash()
    {
        $vcard = new VCard();
        $builder = new VCardBuilder($vcard);
        $builder->setFileName(['Test', 'FileName', 'Array'], true, '/');

        $this->assertEquals('test-filename-array', $builder->getFileName());
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
     * Test hasProperty is true
     *
     * @throws \JeroenDesloovere\VCard\Exception\OutputDirectoryNotExistsException
     */
    public function testSave()
    {
        $vcard = new VCard();
        $builder = new VCardBuilder($vcard);
        $this->assertFalse(vfsStreamWrapper::getRoot()->hasChild($builder->getFullFileName()));

        $builder->save(vfsStream::url('exampleDir'));

        $this->assertTrue(vfsStreamWrapper::getRoot()->hasChild($builder->getFullFileName()));
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
