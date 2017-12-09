<?php

namespace JeroenDesloovere\VCard\Tests\Exception;

use JeroenDesloovere\VCard\Exception\ElementAlreadyExistsException;
use JeroenDesloovere\VCard\Exception\EmptyUrlException;
use JeroenDesloovere\VCard\Exception\InvalidImageException;
use JeroenDesloovere\VCard\Exception\InvalidUrlException;
use JeroenDesloovere\VCard\Exception\InvalidVersionException;
use JeroenDesloovere\VCard\Exception\OutputDirectoryNotExistsException;
use JeroenDesloovere\VCard\Exception\VCardException;
use PHPUnit\Framework\TestCase;

/**
 * Class VCardExceptionTest
 *
 * @package JeroenDesloovere\VCard\Tests\Exception
 */
class VCardExceptionTest extends TestCase
{
    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\VCardException
     * @expectedExceptionMessage Testing the VCard error.
     *
     * @throws VCardException
     */
    public function testVCardException()
    {
        throw new VCardException('Testing the VCard error.');
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\ElementAlreadyExistsException
     * @expectedExceptionMessage You can only set "Testing the VCard error." once.
     *
     * @throws ElementAlreadyExistsException
     */
    public function testElementAlreadyExistsException()
    {
        throw new ElementAlreadyExistsException('Testing the VCard error.');
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\EmptyUrlException
     * @expectedExceptionMessage Nothing returned from URL.
     *
     * @throws EmptyUrlException
     */
    public function testEmptyUrlException()
    {
        throw new EmptyUrlException();
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\InvalidImageException
     * @expectedExceptionMessage Returned data is not an image.
     *
     * @throws InvalidImageException
     */
    public function testInvalidImageException()
    {
        throw new InvalidImageException();
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\InvalidUrlException
     * @expectedExceptionMessage Invalid Url.
     *
     * @throws InvalidUrlException
     */
    public function testInvalidUrlException()
    {
        throw new InvalidUrlException();
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\InvalidVersionException
     * @expectedExceptionMessage Invalid VCard version.
     *
     * @throws InvalidVersionException
     */
    public function testInvalidVersionException()
    {
        throw new InvalidVersionException();
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\OutputDirectoryNotExistsException
     * @expectedExceptionMessage Output directory does not exist.
     *
     * @throws OutputDirectoryNotExistsException
     */
    public function testOutputDirectoryNotExistsException()
    {
        throw new OutputDirectoryNotExistsException();
    }
}
