<?php

namespace JeroenDesloovere\VCard\tests;

/*
 * This file is part of the VCard PHP Class from Jeroen Desloovere.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use JeroenDesloovere\VCard\Model\VCard;
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

//    /**
//     *
//     */
//    public function testAddPhotoWithJpgPhoto()
//    {
//        $return = $this->builder->addPhoto(__DIR__.'/image.jpg');
//
//        $this->assertEquals($this->builder, $return);
//    }
//
//    /**
//     *
//     */
//    public function testAddPhotoWithRemoteJpgPhoto()
//    {
//        $return = $this->builder->addPhoto(
//            'https://raw.githubusercontent.com/jeroendesloovere/vcard/master/tests/image.jpg'
//        );
//
//        $this->assertEquals($this->builder, $return);
//    }
//
//    /**
//     * Test adding remote empty photo
//     *
//     * @expectedException \JeroenDesloovere\VCard\Exception\InvalidImageException
//     * @expectedExceptionMessage Returned data is not an image.
//     */
//    public function testAddPhotoWithRemoteEmptyJpgPhoto()
//    {
//        $this->builder->addPhoto(
//            'https://raw.githubusercontent.com/jeroendesloovere/vcard/master/tests/empty.jpg'
//        );
//    }
//
//    /**
//     *
//     */
//    public function testAddLogoWithJpgImage()
//    {
//        $return = $this->builder->addLogo(__DIR__.'/image.jpg');
//
//        $this->assertEquals($this->builder, $return);
//    }
//
//    /**
//     *
//     */
//    public function testAddLogoWithJpgImageNoInclude()
//    {
//        $return = $this->builder->addLogo(__DIR__.'/image.jpg', false);
//
//        $this->assertEquals($this->builder, $return);
//    }

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

//    /**
//     * Test adding local photo using an empty file
//     *
//     * @expectedException \JeroenDesloovere\VCard\Exception\InvalidImageException
//     * @expectedExceptionMessage Returned data is not an image.
//     */
//    public function testAddPhotoWithEmptyFile()
//    {
//        $this->builder->addPhoto(__DIR__.'/emptyfile');
//    }
//
//    /**
//     * Test adding logo with no value
//     *
//     * @expectedException \JeroenDesloovere\VCard\Exception\InvalidImageException
//     * @expectedExceptionMessage Returned data is not an image.
//     */
//    public function testAddLogoWithNoValue()
//    {
//        $this->builder->addLogo(__DIR__.'/emptyfile');
//    }
//
//    /**
//     * Test adding photo with no photo
//     *
//     * @expectedException \JeroenDesloovere\VCard\Exception\InvalidImageException
//     * @expectedExceptionMessage Returned data is not an image.
//     */
//    public function testAddPhotoWithNoPhoto()
//    {
//        $this->builder->addPhoto(__DIR__.'/wrongfile');
//    }
//
//    /**
//     * Test adding logo with no image
//     *
//     * @expectedException \JeroenDesloovere\VCard\Exception\InvalidImageException
//     * @expectedExceptionMessage Returned data is not an image.
//     */
//    public function testAddLogoWithNoImage()
//    {
//        $this->builder->addLogo(__DIR__.'/wrongfile');
//    }

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
                $this->assertContains('EMAIL;INTERNET;'.$key.':'.$email, $output);
            } else {
                $this->assertContains('EMAIL;INTERNET:'.$email, $output);
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
}
