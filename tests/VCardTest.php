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
use JeroenDesloovere\VCard\Property\Email;
use JeroenDesloovere\VCard\Property\Gender;
use JeroenDesloovere\VCard\Property\Logo;
use JeroenDesloovere\VCard\Property\Name;
use JeroenDesloovere\VCard\Property\FullName;
use JeroenDesloovere\VCard\Property\Nickname;
use JeroenDesloovere\VCard\Property\Note;
use JeroenDesloovere\VCard\Property\Parameter\Kind;
use JeroenDesloovere\VCard\Property\Parameter\Revision;
use JeroenDesloovere\VCard\Property\Parameter\Type;
use JeroenDesloovere\VCard\Property\Parameter\Version;
use JeroenDesloovere\VCard\Property\Photo;
use JeroenDesloovere\VCard\Property\Telephone;
use JeroenDesloovere\VCard\Property\Title;
use JeroenDesloovere\VCard\Property\Role;
use JeroenDesloovere\VCard\VCard;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

/**
 * How to execute all tests: `vendor/bin/phpunit tests`
 * Test a single test: `vendor/bin/phpunit tests --filter testMultipleNotAllowedProperties`
 */
final class VCardTest extends TestCase
{
    /** @var VCard */
    protected static $firstVCard;

    /** @var VCard */
    protected static $secondVCard;

    /** @var VCard */
    protected static $thirdVCard;

    /** @var VCard */
    protected static $fourthVCard;

    /** @var vfsStreamDirectory - We save the generated vCard to a virtual storage */
    private $virtualStorage;

    /* Setup, called for only once */
    public static function setUpBeforeClass(): void
    {
        self::setUpFirstVCard();
        self::setUpSecondVCard();
        self::setUpThirdVCard();
        self::setUpFourthCard();
    }

    /* Setup, called for each test case */
    protected function setUp(): void
    {
        $this->virtualStorage = vfsStream::setup();
    }

    /******************************************************
     *                                                    *
     *   Helper methods during first setup                *
     *                                                    *
     *****************************************************/
    private static function setUpFirstVCard(): void
    {
        self::$firstVCard = (new VCard())
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
            ->add(new Telephone('+33-05-42-41-96', Type::work()));
    }

    private static function setUpSecondVCard(): void
    {
        self::$secondVCard = (new VCard())
            ->add(new Name('Doe', 'John'))
            ->add(new Address(null, 'Penthouse', 'Korenmarkt 1', 'Gent', 'Oost-Vlaanderen', '9000', 'België', Type::work()));
    }

    private static function setUpThirdVCard(): void
    {
        self::$thirdVCard = (new VCard(Kind::organization()))
            ->add(new FullName('Apple'))
            ->add(new Title('Apple'))
            ->add(new Role('Fruit'))
            ->add(new Photo(__DIR__ . '/assets/landscape.jpeg'))
            ->add(new Logo(__DIR__ . '/assets/landscape.jpeg'))
            ->add(new Telephone('+32 486 00 00 00'));
    }

    private static function setUpFourthCard(): void
    {
        // Order of creation should match vCard vcf file?
        self::$fourthVCard = (new VCard())
            ->add(Gender::male())
            ->add(new Nickname('danger89'))
            ->add(new FullName('Melroy Antoine van den Berg'))
            ->add(new Address(null, null, 'Poort 35', 'Tiel', 'Gelderland', '7530DA', 'Nederland'))
            ->add(new Email('test@melroy.org'))
            ->add(new Note('This is me.'))
            ->add(new Birthdate(new \DateTime('1989-12-07')))
            ->add(new Telephone('+31603857291'));
    }

    /******************************************************
     *                                                    *
     *   Intergration test cases                          *
     *                                                    *
     *****************************************************/
    public function testFormatterSavingMultipleVCardsToVcfFile(): void
    {
        // Saving "vcards-export.vcf"
        $formatter = new Formatter(new VcfFormatter(), 'vcards-export');
        $formatter->addVCard(self::$firstVCard);
        $formatter->addVCard(self::$secondVCard);

        $this->assertFalse($this->virtualStorage->hasChild('vcards-export.vcf'));
        $formatter->save($this->virtualStorage->url());
        $this->assertTrue($this->virtualStorage->hasChild('vcards-export.vcf'));
    }

    public function testFormatterSavingOneVCardToVcfFile(): void
    {
        // Saving "vcard-export.vcf"
        $formatter = new Formatter(new VcfFormatter(), 'vcard-export');
        $formatter->addVCard(self::$firstVCard);

        $this->assertFalse($this->virtualStorage->hasChild('vcard-export.vcf'));
        $formatter->save($this->virtualStorage->url());
        $this->assertTrue($this->virtualStorage->hasChild('vcard-export.vcf'));
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\VCardException
     */
    public function testMultipleNotAllowedProperties(): void
    {
        (new VCard())
            ->add(new Nickname('Jeroen'))
            ->add(new Nickname('Jeroen2'));
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\VCardException
     */
    public function testMultipleNotAllowedPropertyParameters(): void
    {
        (new VCard())
            ->add(new Revision(new \DateTime))
            ->add(new Revision(new \DateTime));
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\ParserException
     * @expectedExceptionMessage File "Lorem ipsum dolor sit amet, consectetur adipiscing elit." is not readable, or doesn't exist.
     */
    public function testParserCorruptVCard(): void
    {
        new Parser(new VcfParser(), 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\ParserException
     * @expectedExceptionMessage File "" is not readable, or doesn't exist.
     */
    public function testParserEmptyVCard(): void
    {
        new Parser(new VcfParser(), '');
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\ParserException
     */
    public function testParserGetFileContentsException(): void
    {
        Parser::getFileContents(__DIR__ . '/not-existing');
    }


    /*
     * Integration test:
     * Test the parser with multiple vcard objects from single vcf file
     * Input file from assets: vcards.vcf
     */
    public function testParserMultipleVCardsFromVcfFile(): void
    {
        $parser = new Parser(new VcfParser(), Parser::getFileContents(__DIR__ . '/assets/vcards.vcf'));

        $this->assertEquals(self::$firstVCard->getProperties(), $parser->getVCards()[0]->getProperties());
        $this->assertEquals(self::$secondVCard->getProperties(), $parser->getVCards()[1]->getProperties());
    }

    /**
     * Integration test:
     * Test the parser with a single vcard object from vcf file
     * Input file from assets: vcard1.vcf
     */
    public function testParserOneVCardFromVcfFile1(): void
    {
        $parser = new Parser(new VcfParser(), Parser::getFileContents(__DIR__ . '/assets/vcard1.vcf'));

        $this->assertEquals(self::$firstVCard->getProperties(), $parser->getVCards()[0]->getProperties());
    }

    /**
     * Integration test:
     * Test the parser with a single vcard object from vcf file
     * Input file from assets: vcard2.vcf
     */
    public function testParserOneVCardFromVcfFile2(): void
    {
        $parser = new Parser(new VcfParser(), Parser::getFileContents(__DIR__ . '/assets/vcard2.vcf'));

        $this->assertEquals(self::$fourthVCard->getProperties(), $parser->getVCards()[0]->getProperties());
    }

    /**
     * Integration test:
     * Validate the number of properties from the created vCards in the Setup.
     */
    public function testVCardGetProperties(): void
    {
        $this->assertCount(13, self::$firstVCard->getProperties());
        $this->assertCount(1, self::$firstVCard->getProperties(Gender::class));
        $this->assertCount(1, self::$firstVCard->getProperties(Nickname::class));
        // FullName is created based on the Name (N)
        $this->assertCount(1, self::$firstVCard->getProperties(FullName::class));
        $this->assertCount(1, self::$firstVCard->getProperties(Name::class));
        $this->assertCount(2, self::$firstVCard->getProperties(Address::class));
        $this->assertCount(2, self::$firstVCard->getProperties(Email::class));
        $this->assertCount(1, self::$firstVCard->getProperties(Note::class));
        $this->assertCount(1, self::$firstVCard->getProperties(Birthdate::class));
        $this->assertCount(1, self::$firstVCard->getProperties(Anniversary::class));
        $this->assertCount(2, self::$firstVCard->getProperties(Telephone::class));

        $this->assertCount(3, self::$secondVCard->getProperties());
        // FullName is created based on the Name (N)
        $this->assertCount(1, self::$secondVCard->getProperties(FullName::class));
        $this->assertCount(1, self::$secondVCard->getProperties(Name::class));
        $this->assertCount(1, self::$secondVCard->getProperties(Address::class));

        $this->assertCount(6, self::$thirdVCard->getProperties());
        $this->assertCount(1, self::$thirdVCard->getProperties(FullName::class));
        $this->assertCount(1, self::$thirdVCard->getProperties(Title::class));
        $this->assertCount(1, self::$thirdVCard->getProperties(Role::class));
        $this->assertCount(1, self::$thirdVCard->getProperties(Photo::class));
        $this->assertCount(1, self::$thirdVCard->getProperties(Logo::class));
        $this->assertCount(1, self::$thirdVCard->getProperties(Telephone::class));

        $this->assertCount(8, self::$fourthVCard->getProperties());
        $this->assertCount(1, self::$fourthVCard->getProperties(Gender::class));
        $this->assertCount(1, self::$fourthVCard->getProperties(Nickname::class));
        $this->assertCount(1, self::$fourthVCard->getProperties(FullName::class));
        $this->assertCount(1, self::$fourthVCard->getProperties(Email::class));
        $this->assertCount(1, self::$fourthVCard->getProperties(Address::class));
        $this->assertCount(1, self::$fourthVCard->getProperties(Note::class));
        $this->assertCount(1, self::$fourthVCard->getProperties(Telephone::class));
        $this->assertCount(1, self::$fourthVCard->getProperties(Birthdate::class));
    }

    /******************************************************
     *                                                    *
     *   Unit test cases                                  *
     *    (only test a single unit ('methode') at a time) *
     *****************************************************/

    /**
     * Validate the vcard2.vcf (fourthVcard) content
     */
    public function testVcard2ObjectContent(): void
    {
      // Given
      $expectedContent = "BEGIN:VCARD\r\n" .
        "VERSION:4.0\r\n" .
        "KIND:individual\r\n" .
        "FN:Melroy Antoine van den Berg\r\n" .
        "GENDER:M\r\n" .
        "NICKNAME:danger89\r\n" .
        "ADR;TYPE=home:;;Poort 35;Tiel;Gelderland;7530DA;Nederland\r\n" .
        "EMAIL;TYPE=home:test@melroy.org\r\n" .
        "NOTE:This is me.\r\n" .
        "BDAY:19891207T000000\r\n" .
        "TEL;TYPE=home;VALUE=uri:tel:+31603857291\r\n" .
        "END:VCARD\r\n";

      $formatter = new Formatter(new VcfFormatter(), '');

      // When
      $formatter->addVCard(self::$fourthVCard);

      // Then
      $this->assertEquals($expectedContent, $formatter->getContent());
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

    /**
     * Verify if multiple telephone numbers are correctly formatted
     */
    public function testTelephonePropertyContent(): void
    {
      // Given
      $expectedContent = "BEGIN:VCARD\r\n" .
        "VERSION:4.0\r\n" .
        "KIND:individual\r\n" .
        "FN:Bob\r\n" .
        "TEL;TYPE=home;VALUE=uri:tel:+33-01-23-45-67\r\n" .
        "TEL;TYPE=work;VALUE=uri:tel:+33-05-42-41-96\r\n" .
        "END:VCARD\r\n";

      $formatter = new Formatter(new VcfFormatter(), '');
      $vcard = (new VCard())
        ->add(new FullName('Bob'))
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
        "FN:Mr. Melroy Antoine van den Berg\r\n" .
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
        "FN:Bob\r\n" .
        "ADR;TYPE=home:42;Villa;Main Street 500;London;Barnet;EN4 0AG;United Kingd\r\n" .
        // Line break because of 75 octets width limit, immediately followed by a single white space.
        " om\r\n" .
        "END:VCARD\r\n";
      $formatter = new Formatter(new VcfFormatter(), '');
      $vcard = (new VCard())
        ->add(new FullName('Bob'))
        ->add(new Address('42', 'Villa', 'Main Street 500', 'London', 'Barnet', 'EN4 0AG', 'United Kingdom'));

      // When
      $formatter->addVCard($vcard);

      // Then
      $this->assertEquals($expectedContent, $formatter->getContent());
    }
}
