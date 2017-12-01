<?php

namespace JeroenDesloovere\VCard\Tests\Util;

use JeroenDesloovere\VCard\Util\GeneralUtil;
use PHPUnit\Framework\TestCase;

/**
 * Class GeneralUtilTest
 *
 * @package JeroenDesloovere\VCard\Tests\Util
 */
class GeneralUtilTest extends TestCase
{
    /**
     *
     */
    public function testParseKeyEmptyArray()
    {
        $result = GeneralUtil::parseKey([]);
        $this->assertEquals('default', $result);
    }

    /**
     *
     */
    public function testParseKeyEmptyArrayDefault()
    {
        $result = GeneralUtil::parseKey([], 'testDefault');
        $this->assertEquals('testDefault', $result);
    }

    /**
     *
     */
    public function testParseKeyFilledArray()
    {
        $result = GeneralUtil::parseKey(['test1', 'test2', 'test3']);
        $this->assertEquals('test1;test2;test3', $result);
    }

    /**
     *
     */
    public function testEscapeNoNewLines()
    {
        $string = 'test 123';
        $result = GeneralUtil::escape($string);
        $this->assertEquals('test 123', $result);
    }

    /**
     *
     */
    public function testEscapeNewLines()
    {
        $string = "test 123\ntest 456\r\ntest 789";
        $result = GeneralUtil::escape($string);
        $this->assertEquals("test 123\\ntest 456\\ntest 789", $result);
    }

    /**
     *
     */
    public function testUnescapeNoNewLines()
    {
        $string = 'test 123';
        $result = GeneralUtil::unescape($string);
        $this->assertEquals('test 123', $result);
    }

    /**
     *
     */
    public function testUnescapeNewLines()
    {
        $string = "test 123\\ntest 456\\ntest 789";
        $result = GeneralUtil::unescape($string);
        $this->assertEquals('test 123'.PHP_EOL.'test 456'.PHP_EOL.'test 789', $result);
    }

    /**
     *
     */
    public function testFold11Chars()
    {
        $result = GeneralUtil::fold('test string');
        $this->assertEquals('test string', $result);
    }

    /**
     *
     */
    public function testFold75Chars()
    {
        $result = GeneralUtil::fold('Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo li');
        $this->assertEquals('Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo li', $result);
    }

    /**
     *
     */
    public function testFold200Chars()
    {
        $result = GeneralUtil::fold('Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec qu');
        $this->assertEquals("Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo \r\n ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis d\r\n is parturient montes, nascetur ridiculus mus. Donec qu", $result);
    }

    /**
     * @throws \JeroenDesloovere\VCard\Exception\InvalidUrlException
     */
    public function testGetMimeTypeWithJpgPhoto()
    {
        $result = GeneralUtil::getMimeType(__DIR__.'/../image.jpg');
        $this->assertEquals('image/jpeg', $result);
    }

    /**
     * @throws \JeroenDesloovere\VCard\Exception\InvalidUrlException
     */
    public function testGetMimeTypeWithRemoteJpgPhoto()
    {
        $result = GeneralUtil::getMimeType('https://raw.githubusercontent.com/jeroendesloovere/vcard/master/tests/image.jpg');
        $this->assertEquals('image/jpeg', $result);
    }

    /**
     * @throws \JeroenDesloovere\VCard\Exception\InvalidUrlException
     */
    public function testGetMimeTypeWithRemoteEmptyJpgPhoto()
    {
        $result = GeneralUtil::getMimeType('https://raw.githubusercontent.com/jeroendesloovere/vcard/master/tests/empty.jpg');
        $this->assertEquals('text/plain', $result);
    }

    /**
     * @throws \JeroenDesloovere\VCard\Exception\InvalidUrlException
     */
    public function testGetMimeTypeWithEmptyFile()
    {
        $result = GeneralUtil::getMimeType(__DIR__.'/../emptyfile');
        $this->assertEquals('inode/x-empty', $result);
    }

    /**
     * @throws \JeroenDesloovere\VCard\Exception\InvalidUrlException
     */
    public function testGetMimeTypeWithNoPhoto()
    {
        $result = GeneralUtil::getMimeType(__DIR__.'/../wrongfile');
        $this->assertEquals('text/plain', $result);
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\InvalidUrlException
     * @expectedExceptionMessage Invalid Url.
     *
     * @throws \JeroenDesloovere\VCard\Exception\InvalidUrlException
     */
    public function testGetMimeTypeWithEmptyStringInput()
    {
        GeneralUtil::getMimeType('');
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\InvalidUrlException
     * @expectedExceptionMessage Invalid Url.
     *
     * @throws \JeroenDesloovere\VCard\Exception\InvalidUrlException
     */
    public function testGetMimeTypeWithSpaceStringInput()
    {
        GeneralUtil::getMimeType(' ');
    }
}
