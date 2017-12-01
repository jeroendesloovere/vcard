<?php

namespace JeroenDesloovere\VCard\Tests\Exception;

/*
 * This file is part of the VCard PHP Class from Jeroen Desloovere.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use JeroenDesloovere\VCard\Exception\ElementAlreadyExistsException;
use JeroenDesloovere\VCard\Exception\EmptyUrlException;
use JeroenDesloovere\VCard\Exception\InvalidImageException;
use JeroenDesloovere\VCard\Exception\InvalidUrlException;
use JeroenDesloovere\VCard\Exception\InvalidVersionException;
use JeroenDesloovere\VCard\Exception\OutputDirectoryNotExistsException;
use JeroenDesloovere\VCard\Exception\VCardException;
use PHPUnit\Framework\TestCase;

/**
 * VCard Exception Test.
 */
class VCardExceptionTest extends TestCase
{
    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\VCardException
     * @expectedExceptionMessage Testing the VCard error.
     */
    public function testVCardException()
    {
        throw new VCardException('Testing the VCard error.');
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\ElementAlreadyExistsException
     * @expectedExceptionMessage You can only set "Testing the VCard error." once.
     */
    public function testElementAlreadyExistsException()
    {
        throw new ElementAlreadyExistsException('Testing the VCard error.');
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\EmptyUrlException
     * @expectedExceptionMessage Nothing returned from URL.
     */
    public function testEmptyUrlException()
    {
        throw new EmptyUrlException();
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\InvalidImageException
     * @expectedExceptionMessage Returned data is not an image.
     */
    public function testInvalidImageException()
    {
        throw new InvalidImageException();
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\InvalidUrlException
     * @expectedExceptionMessage Invalid Url.
     */
    public function testInvalidUrlException()
    {
        throw new InvalidUrlException();
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\InvalidVersionException
     * @expectedExceptionMessage Invalid VCard version.
     */
    public function testInvalidVersionException()
    {
        throw new InvalidVersionException();
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\OutputDirectoryNotExistsException
     * @expectedExceptionMessage Output directory does not exist.
     */
    public function testOutputDirectoryNotExistsException()
    {
        throw new OutputDirectoryNotExistsException();
    }
}
