<?php

namespace JeroenDesloovere\VCard\tests;

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
        $this->assertEquals('Jeroen', $parser->getCardAtIndex(0)->firstname);
        $this->assertEquals('Desloovere', $parser->getCardAtIndex(0)->lastname);
        $this->assertEquals('Jeroen Desloovere', $parser->getCardAtIndex(0)->fullname);
    }

    /**
     *
     */
    public function testBDay()
    {
        $vcard = new VCard();
        $vcard->addBirthday('31-12-2015');
        $parser = new VCardParser($vcard->buildVCard());
        $this->assertEquals('2015-12-31', $parser->getCardAtIndex(0)->birthday->format('Y-m-d'));
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
            'Gitland',
            'WORK;POSTAL'
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
        $this->assertEquals(
            (object) array(
                'name' => 'Lorem Corp.',
                'extended' => '(extended info)',
                'street' => '54th Ipsum Street',
                'city' => 'PHPsville',
                'region' => 'Guacamole',
                'zip' => '01158',
                'country' => 'Gitland',
            ),
            $parser->getCardAtIndex(0)->address['WORK;POSTAL'][0]
        );
        $this->assertEquals(
            (object) array(
                'name' => 'Jeroen Desloovere',
                'extended' => '(extended info, again)',
                'street' => '25th Some Address',
                'city' => 'Townsville',
                'region' => 'Area 51',
                'zip' => '045784',
                'country' => 'Europe (is a country, right?)',
            ),
            $parser->getCardAtIndex(0)->address['WORK;PERSONAL'][0]
        );
        $this->assertEquals(
            (object) array(
                'name' => 'Georges Desloovere',
                'extended' => '(extended info, again, again)',
                'street' => '26th Some Address',
                'city' => 'Townsville-South',
                'region' => 'Area 51B',
                'zip' => '04554',
                'country' => "Europe (no, it isn't)",
            ),
            $parser->getCardAtIndex(0)->address['WORK;PERSONAL'][1]
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
        $this->assertEquals('0984456123', $parser->getCardAtIndex(0)->phone['default'][0]);
        $this->assertEquals('2015123487', $parser->getCardAtIndex(0)->phone['WORK'][0]);
        $this->assertEquals('4875446578', $parser->getCardAtIndex(0)->phone['WORK'][1]);
        $this->assertEquals('9875445464', $parser->getCardAtIndex(0)->phone['PREF;WORK;VOICE'][0]);
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
        $this->assertEquals('some@email.com', $parser->getCardAtIndex(0)->email['INTERNET'][0]);
        $this->assertEquals('site@corp.net', $parser->getCardAtIndex(0)->email['INTERNET;WORK'][0]);
        $this->assertEquals('site.corp@corp.net', $parser->getCardAtIndex(0)->email['INTERNET;WORK'][1]);
        $this->assertEquals('support@info.info', $parser->getCardAtIndex(0)->email['INTERNET;PREF;WORK'][0]);
    }

    /**
     *
     */
    public function testOrganization()
    {
        $vcard = new VCard();
        $vcard->addCompany('Lorem Corp.');
        $parser = new VCardParser($vcard->buildVCard());
        $this->assertEquals('Lorem Corp.', $parser->getCardAtIndex(0)->organization);
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
        $this->assertEquals('http://www.jeroendesloovere.be', $parser->getCardAtIndex(0)->url['default'][0]);
        $this->assertEquals('http://home.example.com', $parser->getCardAtIndex(0)->url['HOME'][0]);
        $this->assertEquals('http://work1.example.com', $parser->getCardAtIndex(0)->url['PREF;WORK'][0]);
        $this->assertEquals('http://work2.example.com', $parser->getCardAtIndex(0)->url['PREF;WORK'][1]);
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

        $this->assertEquals('This is a testnote', $parser->getCardAtIndex(0)->note);
        $this->assertEquals(
            nl2br('This is a multiline note'.PHP_EOL.'New line content!'.PHP_EOL.'Line 2'),
            nl2br($parserMultiline->getCardAtIndex(0)->note)
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

        $this->assertEquals('Category 1', $parser->getCardAtIndex(0)->categories[0]);
        $this->assertEquals('cat-2', $parser->getCardAtIndex(0)->categories[1]);
        $this->assertEquals('another long category!', $parser->getCardAtIndex(0)->categories[2]);
    }

    /**
     *
     */
    public function testTitle()
    {
        $vcard = new VCard();
        $vcard->addJobtitle('Ninja');
        $parser = new VCardParser($vcard->buildVCard());
        $this->assertEquals('Ninja', $parser->getCardAtIndex(0)->title);
    }

    /**
     *
     */
    public function testLogo()
    {
        $image = __DIR__.'/image.jpg';
        $imageUrl = 'https://raw.githubusercontent.com/jeroendesloovere/vcard/master/tests/image.jpg';

        $vcard = new VCard();
        $vcard->addLogo($image, true);
        $parser = new VCardParser($vcard->buildVCard());
        $this->assertStringEqualsFile($image, $parser->getCardAtIndex(0)->rawLogo);

        $vcard = new VCard();
        $vcard->addLogo($image, false);
        $parser = new VCardParser($vcard->buildVCard());
        $this->assertEquals(__DIR__.'/image.jpg', $parser->getCardAtIndex(0)->logo);

        $vcard = new VCard();
        $vcard->addLogo($imageUrl, false);
        $parser = new VCardParser($vcard->buildVCard());
        $this->assertEquals($imageUrl, $parser->getCardAtIndex(0)->logo);
    }

    /**
     *
     */
    public function testPhoto()
    {
        $image = __DIR__.'/image.jpg';
        $imageUrl = 'https://raw.githubusercontent.com/jeroendesloovere/vcard/master/tests/image.jpg';

        $vcard = new VCard();
        $vcard->addPhoto($image, true);
        $parser = new VCardParser($vcard->buildVCard());
        $this->assertStringEqualsFile($image, $parser->getCardAtIndex(0)->rawPhoto);

        $vcard = new VCard();
        $vcard->addPhoto($image, false);
        $parser = new VCardParser($vcard->buildVCard());
        $this->assertEquals(__DIR__.'/image.jpg', $parser->getCardAtIndex(0)->photo);

        $vcard = new VCard();
        $vcard->addPhoto($imageUrl, false);
        $parser = new VCardParser($vcard->buildVCard());
        $this->assertEquals($imageUrl, $parser->getCardAtIndex(0)->photo);
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
        $this->assertEquals('Jeroen Desloovere', $parser->getCardAtIndex(0)->fullname);
        $this->assertEquals('Ipsum Lorem', $parser->getCardAtIndex(1)->fullname);
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
            $this->assertEquals($i === 0 ? 'Jeroen Desloovere' : 'Ipsum Lorem', $card->fullname);
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
        $this->assertEquals('Jeroen', $cards[0]->firstname);
        $this->assertEquals('Desloovere', $cards[0]->lastname);
        $this->assertEquals('Jeroen Desloovere', $cards[0]->fullname);
        // Check the parsing of grouped items as well, which are present in the
        // example file.
        $this->assertEquals('http://www.jeroendesloovere.be', $cards[0]->url['default'][0]);
        $this->assertEquals('site@example.com', $cards[0]->email['INTERNET'][0]);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testFileNotFound()
    {
        VCardParser::parseFromFile(__DIR__.'/does-not-exist.vcf');
    }
}
