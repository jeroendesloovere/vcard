<?php

namespace JeroenDesloovere\VCard\Tests\Model;

use JeroenDesloovere\VCard\Model\VCard;
use JeroenDesloovere\VCard\Model\VCardAddress;
use PHPUnit\Framework\TestCase;

/**
 * Class VCardTest
 *
 * @package JeroenDesloovere\VCard\Tests\Model
 */
class VCardTest extends TestCase
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
    public function testSetAddressesGetAddresses()
    {
        $vcard = new VCard();
        $vcardAddress1 = new VCardAddress();
        $vcardAddress2 = new VCardAddress();
        $vcardAddress3 = new VCardAddress();
        $vcardAddressArray = [$vcardAddress1, $vcardAddress2, $vcardAddress3];
        $vcard->setAddresses($vcardAddressArray);
        $this->assertEquals($vcardAddressArray, $vcard->getAddresses());
    }

    /**
     *
     */
    public function testGetAddressNoAddress()
    {
        $vcard = new VCard();
        $this->assertEquals(null, $vcard->getAddress('toBeNull'));
    }

    /**
     *
     */
    public function testSetPhonesGetPhones()
    {
        $vcard = new VCard();
        $phonesArray = ['', '', ''];
        $vcard->setPhones($phonesArray);
        $this->assertEquals($phonesArray, $vcard->getPhones());
    }

    /**
     *
     */
    public function testGetPhoneNoPhone()
    {
        $vcard = new VCard();
        $this->assertEquals(null, $vcard->getPhone('toBeNull'));
    }

    /**
     *
     */
    public function testSetEmailsGetEmails()
    {
        $vcard = new VCard();
        $emailsArray = ['', '', ''];
        $vcard->setEmails($emailsArray);
        $this->assertEquals($emailsArray, $vcard->getEmails());
    }

    /**
     *
     */
    public function testGetEmailNoEmail()
    {
        $vcard = new VCard();
        $this->assertEquals(null, $vcard->getEmail('toBeNull'));
    }

    /**
     *
     */
    public function testSetRevisionGetRevision()
    {
        $vcard = new VCard();
        $vcard->setRevision('Test revision');
        $this->assertEquals('Test revision', $vcard->getRevision());
    }

    /**
     *
     */
    public function testSetVersionGetVersion()
    {
        $vcard = new VCard();
        $vcard->setVersion('Test version');
        $this->assertEquals('Test version', $vcard->getVersion());
    }

    /**
     *
     */
    public function testSetUrlsGetUrls()
    {
        $vcard = new VCard();
        $urlsArray = ['', '', ''];
        $vcard->setUrls($urlsArray);
        $this->assertEquals($urlsArray, $vcard->getUrls());
    }

    /**
     *
     */
    public function testGetUrlNoUrl()
    {
        $vcard = new VCard();
        $this->assertEquals(null, $vcard->getUrl('toBeNull'));
    }
}
