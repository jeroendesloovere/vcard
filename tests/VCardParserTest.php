<?php

namespace JeroenDesloovere\VCard\tests;

use JeroenDesloovere\VCard\Model\VCardAddress;
use JeroenDesloovere\VCard\VCard;
use JeroenDesloovere\VCard\VCardParser;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for our VCard parser.
 */
class VCardParserTest extends TestCase
{
    /**
     * @expectedException \OutOfBoundsException
     */
    public function testOutOfRangeException()
    {
        $parser = new VCardParser('');
        $parser->getCardAtIndex(2);
    }

    /**
     *
     */
    public function testSimpleVcard()
    {
        $vcard = new VCard();
        $vcard->addName('Desloovere', 'Jeroen');
        $parser = new VCardParser($vcard->buildVCard());
        $this->assertEquals('Jeroen', $parser->getCardAtIndex(0)->getFirstName());
        $this->assertEquals('Desloovere', $parser->getCardAtIndex(0)->getLastName());
        $this->assertEquals('Jeroen Desloovere', $parser->getCardAtIndex(0)->getFullName());
    }

    /**
     *
     */
    public function testBDay()
    {
        $vcard = new VCard();
        $vcard->addBirthday('31-12-2015');
        $parser = new VCardParser($vcard->buildVCard());
        $this->assertEquals('2015-12-31', $parser->getCardAtIndex(0)->getBirthday()->format('Y-m-d'));
    }

    /**
     *
     */
    public function testAddress()
    {
        $vcard = new VCard();
        $vcard->addAddress(
            'Lorem Corp.',
            '(extended info)',
            '54th Ipsum Street',
            'PHPsville',
            'Guacamole',
            '01158',
            'Gitland'
        );
        $vcard->addAddress(
            'Jeroen Desloovere',
            '(extended info, again)',
            '25th Some Address',
            'Townsville',
            'Area 51',
            '045784',
            'Europe (is a country, right?)',
            'WORK;PERSONAL'
        );
        $vcard->addAddress(
            'Georges Desloovere',
            '(extended info, again, again)',
            '26th Some Address',
            'Townsville-South',
            'Area 51B',
            '04554',
            "Europe (no, it isn't)",
            'WORK;PERSONAL'
        );
        $parser = new VCardParser($vcard->buildVCard());

        $lorem = new VCardAddress();
        $lorem->setName('Lorem Corp.');
        $lorem->setExtended('(extended info)');
        $lorem->setStreet('54th Ipsum Street');
        $lorem->setLocality('PHPsville');
        $lorem->setRegion('Guacamole');
        $lorem->setPostalCode('01158');
        $lorem->setCountry('Gitland');
        $this->assertEquals(
            $lorem,
            $parser->getCardAtIndex(0)->getAddress('WORK;POSTAL')[0]
        );

        $jeroen = new VCardAddress();
        $jeroen->setName('Jeroen Desloovere');
        $jeroen->setExtended('(extended info, again)');
        $jeroen->setStreet('25th Some Address');
        $jeroen->setLocality('Townsville');
        $jeroen->setRegion('Area 51');
        $jeroen->setPostalCode('045784');
        $jeroen->setCountry('Europe (is a country, right?)');
        $this->assertEquals(
            $jeroen,
            $parser->getCardAtIndex(0)->getAddress('WORK;PERSONAL')[0]
        );

        $georges = new VCardAddress();
        $georges->setName('Georges Desloovere');
        $georges->setExtended('(extended info, again, again)');
        $georges->setStreet('26th Some Address');
        $georges->setLocality('Townsville-South');
        $georges->setRegion('Area 51B');
        $georges->setPostalCode('04554');
        $georges->setCountry('Europe (no, it isn\'t)');
        $this->assertEquals(
            $georges,
            $parser->getCardAtIndex(0)->getAddress('WORK;PERSONAL')[1]
        );
    }

    /**
     *
     */
    public function testPhone()
    {
        $vcard = new VCard();
        $vcard->addPhoneNumber('0984456123');
        $vcard->addPhoneNumber('2015123487', 'WORK');
        $vcard->addPhoneNumber('4875446578', 'WORK');
        $vcard->addPhoneNumber('9875445464', 'PREF;WORK;VOICE');
        $parser = new VCardParser($vcard->buildVCard());
        $this->assertEquals('0984456123', $parser->getCardAtIndex(0)->getPhones()['default'][0]);
        $this->assertEquals('2015123487', $parser->getCardAtIndex(0)->getPhones()['WORK'][0]);
        $this->assertEquals('4875446578', $parser->getCardAtIndex(0)->getPhones()['WORK'][1]);
        $this->assertEquals('9875445464', $parser->getCardAtIndex(0)->getPhones()['PREF;WORK;VOICE'][0]);
    }

    /**
     *
     */
    public function testEmail()
    {
        $vcard = new VCard();
        $vcard->addEmail('some@email.com');
        $vcard->addEmail('site@corp.net', 'WORK');
        $vcard->addEmail('site.corp@corp.net', 'WORK');
        $vcard->addEmail('support@info.info', 'PREF;WORK');
        $parser = new VCardParser($vcard->buildVCard());
        // The VCard class uses a default type of "INTERNET", so we do not test
        // against the "default" key.
        $this->assertEquals('some@email.com', $parser->getCardAtIndex(0)->getEmails()['INTERNET'][0]);
        $this->assertEquals('site@corp.net', $parser->getCardAtIndex(0)->getEmails()['INTERNET;WORK'][0]);
        $this->assertEquals('site.corp@corp.net', $parser->getCardAtIndex(0)->getEmails()['INTERNET;WORK'][1]);
        $this->assertEquals('support@info.info', $parser->getCardAtIndex(0)->getEmails()['INTERNET;PREF;WORK'][0]);
    }

    /**
     *
     */
    public function testOrganization()
    {
        $vcard = new VCard();
        $vcard->addCompany('Lorem Corp.');
        $parser = new VCardParser($vcard->buildVCard());
        $this->assertEquals('Lorem Corp.', $parser->getCardAtIndex(0)->getOrganization());
    }

    /**
     *
     */
    public function testUrl()
    {
        $vcard = new VCard();
        $vcard->addURL('http://www.jeroendesloovere.be');
        $vcard->addURL('http://home.example.com', 'HOME');
        $vcard->addURL('http://work1.example.com', 'PREF;WORK');
        $vcard->addURL('http://work2.example.com', 'PREF;WORK');
        $parser = new VCardParser($vcard->buildVCard());
        $this->assertEquals('http://www.jeroendesloovere.be', $parser->getCardAtIndex(0)->getUrls()['default'][0]);
        $this->assertEquals('http://home.example.com', $parser->getCardAtIndex(0)->getUrls()['HOME'][0]);
        $this->assertEquals('http://work1.example.com', $parser->getCardAtIndex(0)->getUrls()['PREF;WORK'][0]);
        $this->assertEquals('http://work2.example.com', $parser->getCardAtIndex(0)->getUrls()['PREF;WORK'][1]);
    }

    /**
     *
     */
    public function testNote()
    {
        $vcard = new VCard();
        $vcard->addNote('This is a testnote');
        $parser = new VCardParser($vcard->buildVCard());

        $vcardMultiline = new VCard();
        $vcardMultiline->addNote("This is a multiline note\nNew line content!\r\nLine 2");
        $parserMultiline = new VCardParser($vcardMultiline->buildVCard());

        $this->assertEquals('This is a testnote', $parser->getCardAtIndex(0)->getNote());
        $this->assertEquals(
            nl2br('This is a multiline note'.PHP_EOL.'New line content!'.PHP_EOL.'Line 2'),
            nl2br($parserMultiline->getCardAtIndex(0)->getNote())
        );
    }

    /**
     *
     */
    public function testCategories()
    {
        $vcard = new VCard();
        $vcard->addCategories([
            'Category 1',
            'cat-2',
            'another long category!',
        ]);
        $parser = new VCardParser($vcard->buildVCard());

        $this->assertEquals('Category 1', $parser->getCardAtIndex(0)->getCategories()[0]);
        $this->assertEquals('cat-2', $parser->getCardAtIndex(0)->getCategories()[1]);
        $this->assertEquals('another long category!', $parser->getCardAtIndex(0)->getCategories()[2]);
    }

    /**
     *
     */
    public function testTitle()
    {
        $vcard = new VCard();
        $vcard->addJobtitle('Ninja');
        $parser = new VCardParser($vcard->buildVCard());
        $this->assertEquals('Ninja', $parser->getCardAtIndex(0)->getTitle());
    }

    /**
     *
     */
    public function testLogo()
    {
        $image = __DIR__.'/image.jpg';
        $imageUrl = 'https://raw.githubusercontent.com/jeroendesloovere/vcard/master/tests/image.jpg';

        $vcard = new VCard();
        $vcard->addLogo($image);
        $parser = new VCardParser($vcard->buildVCard());
        $this->assertStringEqualsFile($image, $parser->getCardAtIndex(0)->getRawLogo());

        $vcard = new VCard();
        $vcard->addLogo($image, false);
        $parser = new VCardParser($vcard->buildVCard());
        $this->assertEquals(__DIR__.'/image.jpg', $parser->getCardAtIndex(0)->getLogo());

        $vcard = new VCard();
        $vcard->addLogo($imageUrl, false);
        $parser = new VCardParser($vcard->buildVCard());
        $this->assertEquals($imageUrl, $parser->getCardAtIndex(0)->getLogo());
    }

    /**
     *
     */
    public function testPhoto()
    {
        $image = __DIR__.'/image.jpg';
        $imageUrl = 'https://raw.githubusercontent.com/jeroendesloovere/vcard/master/tests/image.jpg';

        $vcard = new VCard();
        $vcard->addPhoto($image);
        $parser = new VCardParser($vcard->buildVCard());
        $this->assertStringEqualsFile($image, $parser->getCardAtIndex(0)->getRawPhoto());

        $vcard = new VCard();
        $vcard->addPhoto($image, false);
        $parser = new VCardParser($vcard->buildVCard());
        $this->assertEquals(__DIR__.'/image.jpg', $parser->getCardAtIndex(0)->getPhoto());

        $vcard = new VCard();
        $vcard->addPhoto($imageUrl, false);
        $parser = new VCardParser($vcard->buildVCard());
        $this->assertEquals($imageUrl, $parser->getCardAtIndex(0)->getPhoto());
    }

    /**
     *
     */
    public function testVcardDB()
    {
        $db = '';
        $vcard = new VCard();
        $vcard->addName('Desloovere', 'Jeroen');
        $db .= $vcard->buildVCard();

        $vcard = new VCard();
        $vcard->addName('Lorem', 'Ipsum');
        $db .= $vcard->buildVCard();

        $parser = new VCardParser($db);
        $this->assertEquals('Jeroen Desloovere', $parser->getCardAtIndex(0)->getFullName());
        $this->assertEquals('Ipsum Lorem', $parser->getCardAtIndex(1)->getFullName());
    }

    /**
     *
     */
    public function testIteration()
    {
        // Prepare a VCard DB.
        $db = '';
        $vcard = new VCard();
        $vcard->addName('Desloovere', 'Jeroen');
        $db .= $vcard->buildVCard();

        $vcard = new VCard();
        $vcard->addName('Lorem', 'Ipsum');
        $db .= $vcard->buildVCard();

        $parser = new VCardParser($db);
        foreach ($parser as $i => $card) {
            $this->assertEquals($i === 0 ? 'Jeroen Desloovere' : 'Ipsum Lorem', $card->getFullName());
        }
    }

    /**
     *
     */
    public function testFromFile()
    {
        $parser = VCardParser::parseFromFile(__DIR__.'/example.vcf');
        // Use this opportunity to test fetching all cards directly.
        $cards = $parser->getCards();
        $this->assertEquals('Jeroen', $cards[0]->getFirstName());
        $this->assertEquals('Desloovere', $cards[0]->getLastName());
        $this->assertEquals('Jeroen Desloovere', $cards[0]->getFullName());
        // Check the parsing of grouped items as well, which are present in the
        // example file.
        $this->assertEquals('http://www.jeroendesloovere.be', $cards[0]->getUrls()['default'][0]);
        $this->assertEquals('site@example.com', $cards[0]->getEmails()['INTERNET'][0]);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testFileNotFound()
    {
        VCardParser::parseFromFile(__DIR__.'/does-not-exist.vcf');
    }
}
