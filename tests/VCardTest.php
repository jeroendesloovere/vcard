<?php

namespace JeroenDesloovere\VCard\tests;

// required to load
require_once __DIR__ . '/../vendor/autoload.php';

/*
 * This file is part of the VCard PHP Class from Jeroen Desloovere.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use JeroenDesloovere\VCard\VCard;

/**
 * This class will test our VCard PHP Class which can generate VCards.
 *
 * @author Jeroen Desloovere <info@jeroendesloovere.be>
 */
class VCardTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Set up before class
     *
     * @return SocialMedia
     */
    public function setUp()
    {
        $this->vcard = new VCard();
    }

    /**
     * Tear down after class
     */
    public function tearDown()
    {
        $this->vcard = null;
    }

    /**
     * Test first name and last name
     */
    public function testFirstNameAndLastName()
    {
        // define variables
        $firstName = 'Jeroen';
        $lastName = 'Desloovere';

        $this->vcard->addName($lastName, $firstName);

        $this->assertEquals('jeroen_desloovere', $this->vcard->getFilename());
    }

    /**
     * Test full blown name
     */
    public function testFullBlownName()
    {
        // define variables
        $firstName = 'Jeroen';
        $lastName = 'Desloovere';
        $additional = '&';
        $prefix = 'Mister';
        $suffix = 'Junior';

        $this->vcard->addName(
            $lastName,
            $firstName,
            $additional,
            $prefix,
            $suffix
        );

        $this->assertEquals('mister_jeroen_&_desloovere_junior', $this->vcard->getFilename());
    }
}
