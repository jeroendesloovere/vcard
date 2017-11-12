<?php

namespace JeroenDesloovere\VCard;

/*
 * This file is part of the VCard PHP Class from Jeroen Desloovere.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use JeroenDesloovere\VCard\Exception\VCardException;
use PHPUnit\Framework\TestCase;

/**
 * VCard Exception Test.
 */
class VCardExceptionTest extends TestCase
{
    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\VCardException
     */
    public function testException()
    {
        throw new VCardException('Testing the VCard error.');
    }
}
