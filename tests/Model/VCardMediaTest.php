<?php

namespace JeroenDesloovere\VCard\Tests\Model;

use JeroenDesloovere\VCard\Model\VCardMedia;
use PHPUnit\Framework\TestCase;

/**
 * Class VCardMediaTest
 *
 * @package JeroenDesloovere\VCard\Tests\Model
 */
class VCardMediaTest extends TestCase
{
    /**
     * Set up before class
     *
     * @return void
     */
    public function setUp(): void
    {
        // set timezone
        date_default_timezone_set('Europe/Brussels');
    }

    /**
     * @throws \JeroenDesloovere\VCard\Exception\EmptyUrlException
     * @throws \JeroenDesloovere\VCard\Exception\InvalidImageException
     * @throws \JeroenDesloovere\VCard\Exception\InvalidUrlException
     */
    public function testAddUrlMediaWithJpgPhoto()
    {
        $vcardMedia = new VCardMedia();
        $vcardMedia->addUrlMedia(__DIR__.'/../image.jpg');
        $this->assertStringEqualsFile(__DIR__.'/../image.jpg', $vcardMedia->getRaw());
    }

    /**
     * @throws \JeroenDesloovere\VCard\Exception\EmptyUrlException
     * @throws \JeroenDesloovere\VCard\Exception\InvalidImageException
     * @throws \JeroenDesloovere\VCard\Exception\InvalidUrlException
     */
    public function testAddUrlMediaWithRemoteJpgPhoto()
    {
        $vcardMedia = new VCardMedia();
        $vcardMedia->addUrlMedia('https://raw.githubusercontent.com/jeroendesloovere/vcard/master/tests/image.jpg');
        $remoteFile = file_get_contents('https://raw.githubusercontent.com/jeroendesloovere/vcard/master/tests/image.jpg');
        $this->assertEquals($remoteFile, $vcardMedia->getRaw());
    }

    /**
     * @throws \JeroenDesloovere\VCard\Exception\EmptyUrlException
     * @throws \JeroenDesloovere\VCard\Exception\InvalidImageException
     * @throws \JeroenDesloovere\VCard\Exception\InvalidUrlException
     */
    public function testAddLogoWithJpgImageNoInclude()
    {
        $vcardMedia = new VCardMedia();
        $vcardMedia->addUrlMedia(__DIR__.'/../image.jpg', false);
        $this->assertEquals(__DIR__.'/../image.jpg', $vcardMedia->getUrl());
    }

    /**
     * Test adding remote empty photo
     *
     * @expectedException \JeroenDesloovere\VCard\Exception\InvalidImageException
     * @expectedExceptionMessage Returned data is not an image.
     *
     * @throws \JeroenDesloovere\VCard\Exception\EmptyUrlException
     * @throws \JeroenDesloovere\VCard\Exception\InvalidImageException
     * @throws \JeroenDesloovere\VCard\Exception\InvalidUrlException
     */
    public function testAddUrlMediaWithRemoteEmptyJpgPhoto()
    {
        $vcardMedia = new VCardMedia();
        $vcardMedia->addUrlMedia('https://raw.githubusercontent.com/jeroendesloovere/vcard/master/tests/empty.jpg');
    }

    /**
     * Test adding local photo using an empty file
     *
     * @expectedException \JeroenDesloovere\VCard\Exception\InvalidImageException
     * @expectedExceptionMessage Returned data is not an image.
     *
     * @throws \JeroenDesloovere\VCard\Exception\EmptyUrlException
     * @throws \JeroenDesloovere\VCard\Exception\InvalidImageException
     * @throws \JeroenDesloovere\VCard\Exception\InvalidUrlException
     */
    public function testAddUrlMediaWithEmptyFile()
    {
        $vcardMedia = new VCardMedia();
        $vcardMedia->addUrlMedia(__DIR__.'/../emptyfile');
    }

    /**
     * Test adding photo with no photo
     *
     * @expectedException \JeroenDesloovere\VCard\Exception\InvalidImageException
     * @expectedExceptionMessage Returned data is not an image.
     *
     * @throws \JeroenDesloovere\VCard\Exception\EmptyUrlException
     * @throws \JeroenDesloovere\VCard\Exception\InvalidImageException
     * @throws \JeroenDesloovere\VCard\Exception\InvalidUrlException
     */
    public function testAddUrlMediaWithNoPhoto()
    {
        $vcardMedia = new VCardMedia();
        $vcardMedia->addUrlMedia(__DIR__.'/../wrongfile');
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\InvalidUrlException
     * @expectedExceptionMessage Invalid Url.
     *
     * @throws \JeroenDesloovere\VCard\Exception\EmptyUrlException
     * @throws \JeroenDesloovere\VCard\Exception\InvalidImageException
     * @throws \JeroenDesloovere\VCard\Exception\InvalidUrlException
     */
    public function testAddUrlMediaWithEmptyStringInput()
    {
        $vcardMedia = new VCardMedia();
        $vcardMedia->addUrlMedia('');
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\InvalidUrlException
     * @expectedExceptionMessage Invalid Url.
     *
     * @throws \JeroenDesloovere\VCard\Exception\EmptyUrlException
     * @throws \JeroenDesloovere\VCard\Exception\InvalidImageException
     * @throws \JeroenDesloovere\VCard\Exception\InvalidUrlException
     */
    public function testAddUrlMediaWithSpaceStringInput()
    {
        $vcardMedia = new VCardMedia();
        $vcardMedia->addUrlMedia(' ');
    }
}
