<?php

namespace JeroenDesloovere\VCard\Tests\Model;

/*
 * This file is part of the VCard PHP Class from Jeroen Desloovere.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use JeroenDesloovere\VCard\Model\VCardMedia;
use PHPUnit\Framework\TestCase;

/**
 * This class will test our VCard PHP Class which can generate VCards.
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
     *
     */
    public function testAddUrlMediaWithJpgPhoto()
    {
        $vcardMedia = new VCardMedia();
        $vcardMedia->addUrlMedia(__DIR__.'/../image.jpg');
        $this->assertStringEqualsFile(__DIR__.'/../image.jpg', $vcardMedia->getRaw());
    }

    /**
     *
     */
    public function testAddUrlMediaWithRemoteJpgPhoto()
    {
        $vcardMedia = new VCardMedia();
        $vcardMedia->addUrlMedia('https://raw.githubusercontent.com/jeroendesloovere/vcard/master/tests/image.jpg');
        $remoteFile = file_get_contents('https://raw.githubusercontent.com/jeroendesloovere/vcard/master/tests/image.jpg');
        $this->assertEquals($remoteFile, $vcardMedia->getRaw());
    }

    /**
     *
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
     */
    public function testAddUrlMediaWithNoPhoto()
    {
        $vcardMedia = new VCardMedia();
        $vcardMedia->addUrlMedia(__DIR__.'/../wrongfile');
    }
}
