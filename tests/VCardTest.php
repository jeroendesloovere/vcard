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
     * @var VCard
     */
    protected $vcard = null;

    /**
     * Set up before class
     *
     * @return void
     */
    public function setUp()
    {
        $this->vcard = new VCard();

        $this->firstName = 'Jeroen';
        $this->lastName = 'Desloovere';
        $this->additional = '&';
        $this->prefix = 'Mister';
        $this->suffix = 'Junior';

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
        $this->vcard = null;
    }

    /**
     * Test first name and last name
     */
    public function testFirstNameAndLastName()
    {
        $this->vcard->addName(
            $this->lastName,
            $this->firstName
        );

        $this->assertEquals('jeroen-desloovere', $this->vcard->getFilename());
    }

    /**
     * Test special first name and last name
     */
    public function testSpecialFirstNameAndLastName()
    {
        $this->vcard->addName(
            $this->lastName2,
            $this->firstName2
        );

        $this->assertEquals('ali-ozsut', $this->vcard->getFilename());
    }

    /**
     * Test special first name and last name
     */
    public function testSpecialFirstNameAndLastName2()
    {
        $this->vcard->addName(
            $this->lastName3,
            $this->firstName3
        );

        $this->assertEquals('garcon-jeroen', $this->vcard->getFilename());
    }

    /**
     * Test full blown name
     */
    public function testFullBlownName()
    {
        $this->vcard->addName(
            $this->lastName,
            $this->firstName,
            $this->additional,
            $this->prefix,
            $this->suffix
        );

        $this->assertEquals('mister-jeroen-desloovere-junior', $this->vcard->getFilename());
    }

    /**
     * @test
     * @dataProvider emailDataProvider
     */
    public function testEmail($emails = array())
    {
        foreach ($emails as $key => $email) {
            if (is_string($key)) {
                $this->vcard->addEmail($email, $key);
            } else {
                $this->vcard->addEmail($email);
            }
        }

        foreach ($emails as $key => $email) {
            if (is_string($key)) {
                $this->assertContains($key . ':' . $email, $this->vcard->getOutput());
            } else {
                $this->assertContains($email, $this->vcard->getOutput());
            }

        }
    }

    /**
     * data provider for testEmail()
     *
     * @return array
     */
    public function emailDataProvider() {
        return array(
            array(array('john@doe.com')),
            array(array('john@doe.com', 'WORK' => 'john@work.com')),
            array(array('WORK' => 'john@work.com', 'HOME' => 'john@home.com')),
            array(array('PREF;WORK' => 'john@work.com', 'HOME' => 'john@home.com')),
        );
    }
}
