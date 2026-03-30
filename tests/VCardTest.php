<?php

declare(strict_types=1);

namespace JeroenDesloovere\Tests\VCard;

use JeroenDesloovere\VCard\Formatter\Formatter;
use JeroenDesloovere\VCard\Formatter\VcfFormatter;
use JeroenDesloovere\VCard\Parser\Parser;
use JeroenDesloovere\VCard\Parser\VcfParser;
use JeroenDesloovere\VCard\Property\Address;
use JeroenDesloovere\VCard\Property\Anniversary;
use JeroenDesloovere\VCard\Property\Birthdate;
use JeroenDesloovere\VCard\Property\CalAdUri;
use JeroenDesloovere\VCard\Property\CalUri;
use JeroenDesloovere\VCard\Property\Categories;
use JeroenDesloovere\VCard\Property\Email;
use JeroenDesloovere\VCard\Property\FbUrl;
use JeroenDesloovere\VCard\Property\Gender;
use JeroenDesloovere\VCard\Property\Geo;
use JeroenDesloovere\VCard\Property\Impp;
use JeroenDesloovere\VCard\Property\Key;
use JeroenDesloovere\VCard\Property\Lang;
use JeroenDesloovere\VCard\Property\Logo;
use JeroenDesloovere\VCard\Property\Member;
use JeroenDesloovere\VCard\Property\Name;
use JeroenDesloovere\VCard\Property\Nickname;
use JeroenDesloovere\VCard\Property\Note;
use JeroenDesloovere\VCard\Property\Org;
use JeroenDesloovere\VCard\Property\Parameter\Kind;
use JeroenDesloovere\VCard\Property\Parameter\Revision;
use JeroenDesloovere\VCard\Property\Parameter\Type;
use JeroenDesloovere\VCard\Property\Parameter\Version;
use JeroenDesloovere\VCard\Property\Photo;
use JeroenDesloovere\VCard\Property\ProdId;
use JeroenDesloovere\VCard\Property\Related;
use JeroenDesloovere\VCard\Property\Sound;
use JeroenDesloovere\VCard\Property\Telephone;
use JeroenDesloovere\VCard\Property\Title;
use JeroenDesloovere\VCard\Property\Role;
use JeroenDesloovere\VCard\Property\Tz;
use JeroenDesloovere\VCard\Property\Uid;
use JeroenDesloovere\VCard\Property\Url;
use JeroenDesloovere\VCard\VCard;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

/**
 * How to execute all tests: `vendor/bin/phpunit tests`
 */
final class VCardTest extends TestCase
{
    /** @var VCard */
    private $firstVCard;

    /** @var VCard */
    private $secondVCard;

    /** @var VCard */
    private $thirdVCard;

    /** @var vfsStreamDirectory - We save the generated vCard to a virtual storage */
    private $virtualStorage;

    public function setUp(): void
    {
        $this->setUpFirstVCard();
        $this->setUpSecondVCard();
        $this->setUpThirdVCard();
        $this->virtualStorage = vfsStream::setup();
    }

    private function setUpFirstVCard(): void
    {
        $this->firstVCard = (new VCard())
            ->add(Gender::male('Dude'))
            ->add(new Nickname('Web developer'))
            ->add(new Name('Desloovere', 'Jeroen'))
            ->add(new Address(null, null, 'Markt 1', 'Brugge', 'West-Vlaanderen', '8000', 'België', Type::work()))
            ->add(new Address(null, 'Penthouse', 'Korenmarkt 1', 'Gent', 'Oost-Vlaanderen', '9000', 'België', Type::home()))
            ->add(new Email('test@test.nl', Type::work()))
            ->add(new Email('example@example.nl', Type::home()))
            ->add(new Note('VCard library is amazing.'))
            ->add(new Birthdate(new \DateTime('2015-12-05')))
            ->add(new Anniversary(new \DateTime('2017-12-05')))
            ->add(new Telephone('+33 01 23 45 67'))
            ->add(new Telephone('+33-05-42-41-96', Type::work()))
            ->add(new Url('https://www.jeroendesloovere.be'))
        ;
    }

    private function setUpSecondVCard(): void
    {
        $this->secondVCard = (new VCard())
            ->add(new Name('Doe', 'John'))
            ->add(new Address(null, 'Penthouse', 'Korenmarkt 1', 'Gent', 'Oost-Vlaanderen', '9000', 'België', Type::work()));
    }

    private function setUpThirdVCard(): void
    {
        $this->thirdVCard = (new VCard(Kind::organization()))
            ->add(new Title('Apple'))
            ->add(new Role('Fruit'))
            ->add(new Photo(__DIR__ . '/assets/landscape.jpeg'))
            ->add(new Logo(__DIR__ . '/assets/landscape.jpeg'))
            ->add(new Telephone('+32 486 00 00 00'));
    }

    public function testFormatterSavingMultipleVCardsToVcfFile(): void
    {
        // Saving "vcards-export.vcf"
        $formatter = new Formatter(new VcfFormatter(), 'vcards-export');
        $formatter->addVCard($this->firstVCard);
        $formatter->addVCard($this->secondVCard);

        $this->assertFalse($this->virtualStorage->hasChild('vcards-export.vcf'));
        $formatter->save($this->virtualStorage->url());
        $this->assertTrue($this->virtualStorage->hasChild('vcards-export.vcf'));
    }

    public function testFormatterSavingOneVCardToVcfFile(): void
    {
        // Saving "vcard-export.vcf"
        $formatter = new Formatter(new VcfFormatter(), 'vcard-export');
        $formatter->addVCard($this->firstVCard);

        $this->assertFalse($this->virtualStorage->hasChild('vcard-export.vcf'));
        $formatter->save($this->virtualStorage->url());
        $this->assertTrue($this->virtualStorage->hasChild('vcard-export.vcf'));
    }

    public function testMultipleNotAllowedProperties(): void
    {
        $this->expectException(\JeroenDesloovere\VCard\Exception\VCardException::class);
        (new VCard())
            ->add(new Nickname('Jeroen'))
            ->add(new Nickname('Jeroen2'));
    }

    public function testMultipleNotAllowedPropertyParameters(): void
    {
        $this->expectException(\JeroenDesloovere\VCard\Exception\VCardException::class);
        (new VCard())
            ->add(new Revision(new \DateTime))
            ->add(new Revision(new \DateTime));
    }

    public function testParserCorruptVCard(): void
    {
        $this->expectException(\JeroenDesloovere\VCard\Exception\ParserException::class);
        $this->expectExceptionMessage('File "Lorem ipsum dolor sit amet, consectetur adipiscing elit." is not readable, or doesn\'t exist.');
        new Parser(new VcfParser(), 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
    }

    public function testParserEmptyVCard(): void
    {
        $this->expectException(\JeroenDesloovere\VCard\Exception\ParserException::class);
        $this->expectExceptionMessage('File "" is not readable, or doesn\'t exist.');
        new Parser(new VcfParser(), '');
    }

    public function testParserGetFileContentsException(): void
    {
        $this->expectException(\JeroenDesloovere\VCard\Exception\ParserException::class);
        Parser::getFileContents(__DIR__ . '/not-existing');
    }

    /**
     * Test the Telephone parser independently.
     */
    public function testTelephoneParser(): void
    {
        // Given
        $vcard = (new Vcard())->add(new Telephone('+33-01-23-45-67'));
        $content = "BEGIN:VCARD\r\nVERSION:4.0\r\nTEL;VALUE=uri;TYPE=home:tel:+33-01-23-45-67\r\nEND:VCARD";

        // When
        $parser = new Parser(new VcfParser(), $content);

        // Then
        $this->assertEquals($vcard->getProperties(Telephone::class), $parser->getVCards()[0]->getProperties(Telephone::class));
    }

    /**
     * Test the Version parameter parser independently.
     * With version 4 and version 3, should result in not equal (bad weather)
     */
    public function testVersionParameterParserBadWeather(): void
    {
        // Given
        // Version 4
        $vcard = new Vcard(null, Version::version4());
        // Version 3
        $content = "BEGIN:VCARD\r\nVERSION:3.0\r\nEND:VCARD";

        // When
        $parser = new Parser(new VcfParser(), $content);

        // Then
        $this->assertNotEquals($vcard->getParameters(), $parser->getVCards()[0]->getParameters());
    }

    /**
     * Test the Version parameter parser independently.
     * Both version 4 (good weather)
     */
    public function testVersionParameterParserGoodWeather(): void
    {
        // Given
        $vcard = new Vcard(null, Version::version4());
        $content = "BEGIN:VCARD\r\nVERSION:4.0\r\nEND:VCARD";

        // When
        $parser = new Parser(new VcfParser(), $content);

        // Then
        $this->assertEquals($vcard->getParameters(), $parser->getVCards()[0]->getParameters());
    }

    /**
     * Test the Version parameter parser independently.
     * Without any version specified, should use the default Version value (4.0)
     */
    public function testVersionParameterParserWithoutVersion(): void
    {
        // Given
        $vcard = new Vcard();
        $content = "BEGIN:VCARD\r\nEND:VCARD";

        // When
        $parser = new Parser(new VcfParser(), $content);

        // Then
        $this->assertEquals($vcard->getParameters(), $parser->getVCards()[0]->getParameters());
    }

    /**
     * Test the Kind parameter parser independently.
     * Test vcard with individual person (is default kind)
     */
    public function testKindIndividual(): void
    {
        // Given
        $vcard = new Vcard();
        $content = "BEGIN:VCARD\r\nVERSION:4.0\r\nKIND:individual\r\nEND:VCARD";

        // When
        $parser = new Parser(new VcfParser(), $content);

        // Then
        $this->assertEquals($vcard->getParameters(), $parser->getVCards()[0]->getParameters());
    }

    /**
     * Test the Kind parameter parser independently.
     * Test vcard with department/organization
     */
    public function testKindOrganization(): void
    {
        // Given
        $vcard = new Vcard(Kind::organization());
        $content = "BEGIN:VCARD\r\nVERSION:4.0\r\nKIND:org\r\nEND:VCARD";

        // When
        $parser = new Parser(new VcfParser(), $content);

        // Then
        $this->assertEquals($vcard->getParameters(), $parser->getVCards()[0]->getParameters());
    }

    /**
     * Test the Kind parameter parser independently.
     * Test vcard with group
     */
    public function testKindGroup(): void
    {
        // Given
        $vcard = new Vcard(Kind::group());
        $content = "BEGIN:VCARD\r\nVERSION:4.0\r\nKIND:group\r\nEND:VCARD";

        // When
        $parser = new Parser(new VcfParser(), $content);

        // Then
        $this->assertEquals($vcard->getParameters(), $parser->getVCards()[0]->getParameters());
    }

    public function testParserMultipleVCardsFromVcfFile(): void
    {
        $parser = new Parser(new VcfParser(), Parser::getFileContents(__DIR__ . '/assets/vcards.vcf'));

        $this->assertEquals($this->firstVCard->getProperties(), $parser->getVCards()[0]->getProperties());
        $this->assertEquals($this->secondVCard->getProperties(), $parser->getVCards()[1]->getProperties());
    }

    public function testParserOneVCardFromVcfFile(): void
    {
        $parser = new Parser(new VcfParser(), Parser::getFileContents(__DIR__ . '/assets/vcard.vcf'));

        $this->assertEquals($this->firstVCard->getProperties(), $parser->getVCards()[0]->getProperties());
    }

    /**
     * Integration test:
     * Validate the number of properties from the created vCards in the Setup.
     */
    public function testVCardGetProperties(): void
    {
        $this->assertCount(13, $this->firstVCard->getProperties());
        $this->assertCount(1, $this->firstVCard->getProperties(Gender::class));
        $this->assertCount(1, $this->firstVCard->getProperties(Nickname::class));
        $this->assertCount(1, $this->firstVCard->getProperties(Name::class));
        $this->assertCount(2, $this->firstVCard->getProperties(Address::class));
        $this->assertCount(2, $this->firstVCard->getProperties(Email::class));
        $this->assertCount(1, $this->firstVCard->getProperties(Note::class));
        $this->assertCount(1, $this->firstVCard->getProperties(Birthdate::class));
        $this->assertCount(1, $this->firstVCard->getProperties(Anniversary::class));
        $this->assertCount(2, $this->firstVCard->getProperties(Telephone::class));
        $this->assertCount(1, $this->firstVCard->getProperties(Url::class));

        $this->assertCount(2, $this->secondVCard->getProperties());
        $this->assertCount(1, $this->secondVCard->getProperties(Name::class));
        $this->assertCount(1, $this->secondVCard->getProperties(Address::class));

        $this->assertCount(5, $this->thirdVCard->getProperties());
        $this->assertCount(1, $this->thirdVCard->getProperties(Title::class));
        $this->assertCount(1, $this->thirdVCard->getProperties(Role::class));
        $this->assertCount(1, $this->thirdVCard->getProperties(Photo::class));
        $this->assertCount(1, $this->thirdVCard->getProperties(Logo::class));
        $this->assertCount(1, $this->thirdVCard->getProperties(Telephone::class));
    }

    /**
     * Verify if multiple telephone numbers are correctly formatted
     */
    public function testTelephonePropertyContent(): void
    {
      // Given
      $expectedContent = "BEGIN:VCARD\r\n" .
        "VERSION:4.0\r\n" .
        "KIND:individual\r\n" .
        "TEL;TYPE=home;VALUE=uri:tel:+33-01-23-45-67\r\n" .
        "TEL;TYPE=work;VALUE=uri:tel:+33-05-42-41-96\r\n" .
        "END:VCARD\r\n";

      $formatter = new Formatter(new VcfFormatter(), '');
      $vcard = (new VCard())
        ->add(new Telephone('+33 01 23 45 67'))
        ->add(new Telephone('+33-05-42-41-96', Type::work()));

      // When
      $formatter->addVCard($vcard);

      // Then
      $this->assertEquals($expectedContent, $formatter->getContent());
    }

    /**
     * Verify if a full name gets correctly formatted
     */
    public function testNamePropertyContent(): void
    {
      // Given
      $expectedContent = "BEGIN:VCARD\r\n" .
        "VERSION:4.0\r\n" .
        "KIND:individual\r\n" .
        "N:van den Berg;Melroy;Antoine;Mr.;\r\n" .
        "END:VCARD\r\n";

      $formatter = new Formatter(new VcfFormatter(), '');
      $vcard = (new VCard())->add(new Name('van den Berg', 'Melroy', 'Antoine', 'Mr.'));

      // When
      $formatter->addVCard($vcard);

      // Then
      $this->assertEquals($expectedContent, $formatter->getContent());
    }

    /**
     * Verify if an address gets correctly formatted,
     * even with a long text over the 75 chars limit (excl. line breaks)
     */
    public function testAddressPropertyContentWithLineBreak() : void
    {
      // Given
      $expectedContent = "BEGIN:VCARD\r\n" .
        "VERSION:4.0\r\n" .
        "KIND:individual\r\n" .
        "ADR;TYPE=home:42;Villa;Main Street 500;London;Barnet;EN4 0AG;United Kingd\r\n" .
        // Line break because of 75 octets width limit, immediately followed by a single white space.
        " om\r\n" .
        "END:VCARD\r\n";
      $formatter = new Formatter(new VcfFormatter(), '');
      $vcard = (new VCard())->add(new Address('42', 'Villa', 'Main Street 500', 'London', 'Barnet', 'EN4 0AG', 'United Kingdom'));

      // When
      $formatter->addVCard($vcard);

      // Then
      $this->assertEquals($expectedContent, $formatter->getContent());
    }

    public function testTzPropertyContent(): void
    {
        $expectedContent = "BEGIN:VCARD\r\n" .
            "VERSION:4.0\r\n" .
            "KIND:individual\r\n" .
            "TZ:-0500\r\n" .
            "END:VCARD\r\n";

        $formatter = new Formatter(new VcfFormatter(), '');
        $vcard = (new VCard())->add(new Tz('-0500'));

        $formatter->addVCard($vcard);

        $this->assertEquals($expectedContent, $formatter->getContent());
    }

    public function testGeoPropertyContent(): void
    {
        $expectedContent = "BEGIN:VCARD\r\n" .
            "VERSION:4.0\r\n" .
            "KIND:individual\r\n" .
            "GEO:geo:37.386013,-122.082932\r\n" .
            "END:VCARD\r\n";

        $formatter = new Formatter(new VcfFormatter(), '');
        $vcard = (new VCard())->add(new Geo('geo:37.386013,-122.082932'));

        $formatter->addVCard($vcard);

        $this->assertEquals($expectedContent, $formatter->getContent());
    }

    public function testCategoriesPropertyContent(): void
    {
        $expectedContent = "BEGIN:VCARD\r\n" .
            "VERSION:4.0\r\n" .
            "KIND:individual\r\n" .
            "CATEGORIES:TRAVEL AGENT,INTERNET\r\n" .
            "END:VCARD\r\n";

        $formatter = new Formatter(new VcfFormatter(), '');
        $vcard = (new VCard())->add(new Categories('TRAVEL AGENT,INTERNET'));

        $formatter->addVCard($vcard);

        $this->assertEquals($expectedContent, $formatter->getContent());
    }

    public function testProdIdPropertyContent(): void
    {
        $expectedContent = "BEGIN:VCARD\r\n" .
            "VERSION:4.0\r\n" .
            "KIND:individual\r\n" .
            "PRODID:-//My App//EN\r\n" .
            "END:VCARD\r\n";

        $formatter = new Formatter(new VcfFormatter(), '');
        $vcard = (new VCard())->add(new ProdId('-//My App//EN'));

        $formatter->addVCard($vcard);

        $this->assertEquals($expectedContent, $formatter->getContent());
    }

    public function testUidPropertyContent(): void
    {
        $expectedContent = "BEGIN:VCARD\r\n" .
            "VERSION:4.0\r\n" .
            "KIND:individual\r\n" .
            "UID:urn:uuid:f81d4fae-7dec-11d0-a765-00a0c91e6bf6\r\n" .
            "END:VCARD\r\n";

        $formatter = new Formatter(new VcfFormatter(), '');
        $vcard = (new VCard())->add(new Uid('urn:uuid:f81d4fae-7dec-11d0-a765-00a0c91e6bf6'));

        $formatter->addVCard($vcard);

        $this->assertEquals($expectedContent, $formatter->getContent());
    }

    public function testKeyPropertyContent(): void
    {
        $expectedContent = "BEGIN:VCARD\r\n" .
            "VERSION:4.0\r\n" .
            "KIND:individual\r\n" .
            "KEY:data:application/pgp-keys;base64,abc123\r\n" .
            "END:VCARD\r\n";

        $formatter = new Formatter(new VcfFormatter(), '');
        $vcard = (new VCard())->add(new Key('data:application/pgp-keys;base64,abc123'));

        $formatter->addVCard($vcard);

        $this->assertEquals($expectedContent, $formatter->getContent());
    }

    public function testImppPropertyContent(): void
    {
        $expectedContent = "BEGIN:VCARD\r\n" .
            "VERSION:4.0\r\n" .
            "KIND:individual\r\n" .
            "IMPP;TYPE=home:xmpp:user@example.com\r\n" .
            "END:VCARD\r\n";

        $formatter = new Formatter(new VcfFormatter(), '');
        $vcard = (new VCard())->add(new Impp('xmpp:user@example.com'));

        $formatter->addVCard($vcard);

        $this->assertEquals($expectedContent, $formatter->getContent());
    }

    public function testLangPropertyContent(): void
    {
        $expectedContent = "BEGIN:VCARD\r\n" .
            "VERSION:4.0\r\n" .
            "KIND:individual\r\n" .
            "LANG;TYPE=home:en\r\n" .
            "END:VCARD\r\n";

        $formatter = new Formatter(new VcfFormatter(), '');
        $vcard = (new VCard())->add(new Lang('en'));

        $formatter->addVCard($vcard);

        $this->assertEquals($expectedContent, $formatter->getContent());
    }

    public function testFbUrlPropertyContent(): void
    {
        $expectedContent = "BEGIN:VCARD\r\n" .
            "VERSION:4.0\r\n" .
            "KIND:individual\r\n" .
            "FBURL;TYPE=home:http://example.com/busy\r\n" .
            "END:VCARD\r\n";

        $formatter = new Formatter(new VcfFormatter(), '');
        $vcard = (new VCard())->add(new FbUrl('http://example.com/busy'));

        $formatter->addVCard($vcard);

        $this->assertEquals($expectedContent, $formatter->getContent());
    }

    public function testCalAdUriPropertyContent(): void
    {
        $expectedContent = "BEGIN:VCARD\r\n" .
            "VERSION:4.0\r\n" .
            "KIND:individual\r\n" .
            "CALADRURI;TYPE=home:http://cal.example.com/\r\n" .
            "END:VCARD\r\n";

        $formatter = new Formatter(new VcfFormatter(), '');
        $vcard = (new VCard())->add(new CalAdUri('http://cal.example.com/'));

        $formatter->addVCard($vcard);

        $this->assertEquals($expectedContent, $formatter->getContent());
    }

    public function testCalUriPropertyContent(): void
    {
        $expectedContent = "BEGIN:VCARD\r\n" .
            "VERSION:4.0\r\n" .
            "KIND:individual\r\n" .
            "CALURI;TYPE=home:http://cal.example.com/\r\n" .
            "END:VCARD\r\n";

        $formatter = new Formatter(new VcfFormatter(), '');
        $vcard = (new VCard())->add(new CalUri('http://cal.example.com/'));

        $formatter->addVCard($vcard);

        $this->assertEquals($expectedContent, $formatter->getContent());
    }

    public function testRelatedPropertyContent(): void
    {
        $expectedContent = "BEGIN:VCARD\r\n" .
            "VERSION:4.0\r\n" .
            "KIND:individual\r\n" .
            "RELATED;TYPE=home:urn:uuid:f81d4fae-7dec-11d0-a765-00a0c91e6bf6\r\n" .
            "END:VCARD\r\n";

        $formatter = new Formatter(new VcfFormatter(), '');
        $vcard = (new VCard())->add(new Related('urn:uuid:f81d4fae-7dec-11d0-a765-00a0c91e6bf6'));

        $formatter->addVCard($vcard);

        $this->assertEquals($expectedContent, $formatter->getContent());
    }

    public function testOrgPropertyContent(): void
    {
        $expectedContent = "BEGIN:VCARD\r\n" .
            "VERSION:4.0\r\n" .
            "KIND:individual\r\n" .
            "ORG:Example Inc.;Engineering\r\n" .
            "END:VCARD\r\n";

        $formatter = new Formatter(new VcfFormatter(), '');
        $vcard = (new VCard())->add(new Org('Example Inc.', 'Engineering'));

        $formatter->addVCard($vcard);

        $this->assertEquals($expectedContent, $formatter->getContent());
    }

    public function testOrgPropertyContentNoUnits(): void
    {
        $expectedContent = "BEGIN:VCARD\r\n" .
            "VERSION:4.0\r\n" .
            "KIND:individual\r\n" .
            "ORG:Example Inc.\r\n" .
            "END:VCARD\r\n";

        $formatter = new Formatter(new VcfFormatter(), '');
        $vcard = (new VCard())->add(new Org('Example Inc.'));

        $formatter->addVCard($vcard);

        $this->assertEquals($expectedContent, $formatter->getContent());
    }

    public function testMemberPropertyContent(): void
    {
        $expectedContent = "BEGIN:VCARD\r\n" .
            "VERSION:4.0\r\n" .
            "KIND:group\r\n" .
            "MEMBER:urn:uuid:03a0e51f-d1aa-4385-8a53-e29025acd8af\r\n" .
            "END:VCARD\r\n";

        $formatter = new Formatter(new VcfFormatter(), '');
        $vcard = (new VCard(Kind::group()))->add(new Member('urn:uuid:03a0e51f-d1aa-4385-8a53-e29025acd8af'));

        $formatter->addVCard($vcard);

        $this->assertEquals($expectedContent, $formatter->getContent());
    }

    public function testMemberNotAllowedOnIndividualVCard(): void
    {
        $this->expectException(\JeroenDesloovere\VCard\Exception\VCardException::class);
        (new VCard())->add(new Member('urn:uuid:03a0e51f-d1aa-4385-8a53-e29025acd8af'));
    }

    public function testSoundPropertyContent(): void
    {
        $expectedContent = "BEGIN:VCARD\r\n" .
            "VERSION:4.0\r\n" .
            "KIND:individual\r\n" .
            "SOUND:data:audio/basic;base64,abc123\r\n" .
            "END:VCARD\r\n";

        $formatter = new Formatter(new VcfFormatter(), '');
        $vcard = (new VCard())->add(new Sound('data:audio/basic;base64,abc123'));

        $formatter->addVCard($vcard);

        $this->assertEquals($expectedContent, $formatter->getContent());
    }
}
