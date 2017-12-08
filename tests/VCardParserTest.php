<?php

namespace JeroenDesloovere\VCard\Tests;

use JeroenDesloovere\VCard\Model\VCard;
use JeroenDesloovere\VCard\Model\VCardAddress;
use JeroenDesloovere\VCard\Model\VCardMedia;
use JeroenDesloovere\VCard\VCardBuilder;
use JeroenDesloovere\VCard\VCardParser;
use PHPUnit\Framework\TestCase;

/**
 * Class VCardParserTest
 *
 * @package JeroenDesloovere\VCard\Tests
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
        $vcard->setFirstName('Jeroen');
        $vcard->setLastName('Desloovere');
        $builder = new VCardBuilder($vcard);
        $parser = new VCardParser($builder->buildVCard());
        $this->assertEquals(true, $parser->hasCardAtIndex(0));
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
        $vcard->setBirthday(new \DateTime('31-12-2015'));
        $builder = new VCardBuilder($vcard);
        $parser = new VCardParser($builder->buildVCard());
        $this->assertEquals('2015-12-31', $parser->getCardAtIndex(0)->getBirthday()->format('Y-m-d'));
    }

    /**
     *
     */
    public function testAddress()
    {
        $vcard = new VCard();
        $lorem = new VCardAddress();
        $lorem->setName('Lorem Corp.');
        $lorem->setExtended('(extended info)');
        $lorem->setStreet('54th Ipsum Street');
        $lorem->setLocality('PHPsville');
        $lorem->setRegion('Guacamole');
        $lorem->setPostalCode('01158');
        $lorem->setCountry('Gitland');
        $vcard->addAddress($lorem);
        $jeroen = new VCardAddress();
        $jeroen->setName('Jeroen Desloovere');
        $jeroen->setExtended('(extended info, again)');
        $jeroen->setStreet('25th Some Address');
        $jeroen->setLocality('Townsville');
        $jeroen->setRegion('Area 51');
        $jeroen->setPostalCode('045784');
        $jeroen->setCountry('Europe (is a country, right?)');
        $vcard->addAddress($jeroen, 'WORK;PERSONAL');
        $georges = new VCardAddress();
        $georges->setName('Georges Desloovere');
        $georges->setExtended('(extended info, again, again)');
        $georges->setStreet('26th Some Address');
        $georges->setLocality('Townsville-South');
        $georges->setRegion('Area 51B');
        $georges->setPostalCode('04554');
        $georges->setCountry('Europe (no, it isn\'t)');
        $vcard->addAddress($georges, 'WORK;PERSONAL');
        $builder = new VCardBuilder($vcard);
        $parser = new VCardParser($builder->buildVCard());

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
        $vcard->addPhone('0984456123');
        $vcard->addPhone('2015123487', 'WORK');
        $vcard->addPhone('4875446578', 'WORK');
        $vcard->addPhone('9875445464', 'PREF;WORK;VOICE');
        $builder = new VCardBuilder($vcard);
        $parser = new VCardParser($builder->buildVCard());
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
        $builder = new VCardBuilder($vcard);
        $parser = new VCardParser($builder->buildVCard());
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
        $vcard->setOrganization('Lorem Corp.');
        $builder = new VCardBuilder($vcard);
        $parser = new VCardParser($builder->buildVCard());
        $this->assertEquals('Lorem Corp.', $parser->getCardAtIndex(0)->getOrganization());
    }

    /**
     *
     */
    public function testUrl()
    {
        $vcard = new VCard();
        $vcard->addUrl('http://www.jeroendesloovere.be');
        $vcard->addUrl('http://home.example.com', 'HOME');
        $vcard->addUrl('http://work1.example.com', 'PREF;WORK');
        $vcard->addUrl('http://work2.example.com', 'PREF;WORK');
        $builder = new VCardBuilder($vcard);
        $parser = new VCardParser($builder->buildVCard());
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
        $vcard->setNote('This is a testnote');
        $builder = new VCardBuilder($vcard);
        $parser = new VCardParser($builder->buildVCard());

        $vcardMultiline = new VCard();
        $vcardMultiline->setNote("This is a multiline note\nNew line content!\r\nLine 2");
        $builderMultiline = new VCardBuilder($vcardMultiline);
        $parserMultiline = new VCardParser($builderMultiline->buildVCard());

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
        $vcard->setCategories([
            'Category 1',
            'cat-2',
            'another long category!',
        ]);
        $builder = new VCardBuilder($vcard);
        $parser = new VCardParser($builder->buildVCard());

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
        $vcard->setTitle('Ninja');
        $builder = new VCardBuilder($vcard);
        $parser = new VCardParser($builder->buildVCard());
        $this->assertEquals('Ninja', $parser->getCardAtIndex(0)->getTitle());
    }

    /**
     * @throws \JeroenDesloovere\VCard\Exception\EmptyUrlException
     * @throws \JeroenDesloovere\VCard\Exception\InvalidImageException
     * @throws \JeroenDesloovere\VCard\Exception\InvalidUrlException
     */
    public function testLogo()
    {
        $image = __DIR__.'/image.jpg';
        $imageUrl = 'https://raw.githubusercontent.com/jeroendesloovere/vcard/master/tests/image.jpg';

        $vcard = new VCard();
        $vcardMedia = new VCardMedia();
        $imageRaw = file_get_contents($image);
        $vcardMedia->addRawMedia($imageRaw);
        $vcard->setLogo($vcardMedia);
        $builder = new VCardBuilder($vcard);
        $parser = new VCardParser($builder->buildVCard());
        $this->assertEquals($imageRaw, $parser->getCardAtIndex(0)->getLogo()->getRaw());

        $vcard = new VCard();
        $vcardMedia = new VCardMedia();
        $vcardMedia->addUrlMedia($image);
        $vcard->setLogo($vcardMedia);
        $builder = new VCardBuilder($vcard);
        $parser = new VCardParser($builder->buildVCard());
        $this->assertStringEqualsFile($image, $parser->getCardAtIndex(0)->getLogo()->getRaw());

        $vcard = new VCard();
        $vcardMedia = new VCardMedia();
        $vcardMedia->addUrlMedia($image, false);
        $vcard->setLogo($vcardMedia);
        $builder = new VCardBuilder($vcard);
        $parser = new VCardParser($builder->buildVCard());
        $this->assertEquals(__DIR__.'/image.jpg', $parser->getCardAtIndex(0)->getLogo()->getUrl());

        $vcard = new VCard();
        $vcardMedia = new VCardMedia();
        $vcardMedia->addUrlMedia($imageUrl, false);
        $vcard->setLogo($vcardMedia);
        $builder = new VCardBuilder($vcard);
        $parser = new VCardParser($builder->buildVCard());
        $this->assertEquals($imageUrl, $parser->getCardAtIndex(0)->getLogo()->getUrl());
    }

    /**
     * @throws \JeroenDesloovere\VCard\Exception\EmptyUrlException
     * @throws \JeroenDesloovere\VCard\Exception\InvalidImageException
     * @throws \JeroenDesloovere\VCard\Exception\InvalidUrlException
     */
    public function testPhoto()
    {
        $image = __DIR__.'/image.jpg';
        $imageUrl = 'https://raw.githubusercontent.com/jeroendesloovere/vcard/master/tests/image.jpg';

        $vcard = new VCard();
        $vcardMedia = new VCardMedia();
        $imageRaw = file_get_contents($image);
        $vcardMedia->addRawMedia($imageRaw);
        $vcard->setPhoto($vcardMedia);
        $builder = new VCardBuilder($vcard);
        $parser = new VCardParser($builder->buildVCard());
        $this->assertEquals($imageRaw, $parser->getCardAtIndex(0)->getPhoto()->getRaw());

        $vcard = new VCard();
        $vcardMedia = new VCardMedia();
        $vcardMedia->addUrlMedia($image);
        $vcard->setPhoto($vcardMedia);
        $builder = new VCardBuilder($vcard);
        $parser = new VCardParser($builder->buildVCard());
        $this->assertStringEqualsFile($image, $parser->getCardAtIndex(0)->getPhoto()->getRaw());

        $vcard = new VCard();
        $vcardMedia = new VCardMedia();
        $vcardMedia->addUrlMedia($image, false);
        $vcard->setPhoto($vcardMedia);
        $builder = new VCardBuilder($vcard);
        $parser = new VCardParser($builder->buildVCard());
        $this->assertEquals(__DIR__.'/image.jpg', $parser->getCardAtIndex(0)->getPhoto()->getUrl());

        $vcard = new VCard();
        $vcardMedia = new VCardMedia();
        $vcardMedia->addUrlMedia($imageUrl, false);
        $vcard->setPhoto($vcardMedia);
        $builder = new VCardBuilder($vcard);
        $parser = new VCardParser($builder->buildVCard());
        $this->assertEquals($imageUrl, $parser->getCardAtIndex(0)->getPhoto()->getUrl());
    }

    /**
     *
     */
    public function testVcardDB()
    {
        $db = '';
        $vcard = new VCard();
        $vcard->setFirstName('Jeroen');
        $vcard->setLastName('Desloovere');
        $builder = new VCardBuilder($vcard);
        $db .= $builder->buildVCard();

        $vcard = new VCard();
        $vcard->setFirstName('Ipsum');
        $vcard->setLastName('Lorem');
        $builder = new VCardBuilder($vcard);
        $db .= $builder->buildVCard();

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
        $vcard->setFirstName('Jeroen');
        $vcard->setLastName('Desloovere');
        $builder = new VCardBuilder($vcard);
        $db .= $builder->buildVCard();

        $vcard = new VCard();
        $vcard->setFirstName('Ipsum');
        $vcard->setLastName('Lorem');
        $builder = new VCardBuilder($vcard);
        $db .= $builder->buildVCard();

        $parser = new VCardParser($db);
        foreach ($parser->getCards() as $i => $card) {
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
     *
     */
    public function testEncoding()
    {
        $parser = VCardParser::parseFromFile(__DIR__.'/encoding.vcf');
        // Use this opportunity to test fetching all cards directly.
        $cards = $parser->getCards();

        // normal input
        // utf-8
        $this->assertEquals('Thies-Tillman', $cards[0]->getFirstName());
        $this->assertEquals('Jacobsen', $cards[0]->getLastName());
        $this->assertEquals('Thies-Tillman Jacobsen', $cards[0]->getFullName());
        $this->assertEquals('Lieblingsfarbe: Violett', $cards[0]->getNote());

        // QUOTED-PRINTABLE
        $this->assertEquals('Thies-Tillman', $cards[1]->getFirstName());
        $this->assertEquals('Jacobsen', $cards[1]->getLastName());
        $this->assertEquals('Thies-Tillman Jacobsen', $cards[1]->getFullName());
        $this->assertEquals('Lieblingsfarbe: Violett', $cards[1]->getNote());

        // BASE64
        $this->assertEquals('Thies-Tillman', $cards[2]->getFirstName());
        $this->assertEquals('Jacobsen', $cards[2]->getLastName());
        $this->assertEquals('Thies-Tillman Jacobsen', $cards[2]->getFullName());
        $this->assertEquals('Lieblingsfarbe: Violett', $cards[2]->getNote());

        // ENCODING=b
        $this->assertEquals('Thies-Tillman', $cards[3]->getFirstName());
        $this->assertEquals('Jacobsen', $cards[3]->getLastName());
        $this->assertEquals('Thies-Tillman Jacobsen', $cards[3]->getFullName());
        $this->assertEquals('Lieblingsfarbe: Violett', $cards[3]->getNote());

        // strange input
        // utf-8
        $this->assertEquals('Thies-Tillman', $cards[4]->getFirstName());
        $this->assertEquals('Jacobsen', $cards[4]->getLastName());
        $this->assertEquals('Thies-Tillman Jacobsen', $cards[4]->getFullName());
        $this->assertEquals('Ludwig-Götz Graßl', $cards[4]->getNote());

        // QUOTED-PRINTABLE
        $this->assertEquals('Thies-Tillman', $cards[5]->getFirstName());
        $this->assertEquals('Jacobsen', $cards[5]->getLastName());
        $this->assertEquals('Thies-Tillman Jacobsen', $cards[5]->getFullName());
        $this->assertEquals('Ludwig-Götz Graßl', $cards[5]->getNote());

        // BASE64
        $this->assertEquals('Thies-Tillman', $cards[6]->getFirstName());
        $this->assertEquals('Jacobsen', $cards[6]->getLastName());
        $this->assertEquals('Thies-Tillman Jacobsen', $cards[6]->getFullName());
        $this->assertEquals('Ludwig-Götz Graßl', $cards[6]->getNote());

        // ENCODING=b
        $this->assertEquals('Thies-Tillman', $cards[7]->getFirstName());
        $this->assertEquals('Jacobsen', $cards[7]->getLastName());
        $this->assertEquals('Thies-Tillman Jacobsen', $cards[7]->getFullName());
        $this->assertEquals('Ludwig-Götz Graßl', $cards[7]->getNote());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testFileNotFound()
    {
        VCardParser::parseFromFile(__DIR__.'/does-not-exist.vcf');
    }
}
