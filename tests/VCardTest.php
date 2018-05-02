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
use JeroenDesloovere\VCard\Property\Nickname;
use JeroenDesloovere\VCard\Property\Note;
use JeroenDesloovere\VCard\Property\Parameter\Kind;
use JeroenDesloovere\VCard\Property\Parameter\Revision;
use JeroenDesloovere\VCard\Property\Parameter\Type;
use JeroenDesloovere\VCard\Property\Photo;
use JeroenDesloovere\VCard\Property\Telephone;
use JeroenDesloovere\VCard\Property\Title;
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
            ->add(new Telephone('+33 01 23 45 67'));
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

    public function testVCardGetProperties(): void
    {
        $this->assertCount(11, $this->firstVCard->getProperties());
        $this->assertCount(1, $this->firstVCard->getProperties(Gender::class));
        $this->assertCount(1, $this->firstVCard->getProperties(Nickname::class));
        $this->assertCount(1, $this->firstVCard->getProperties(Name::class));
        $this->assertCount(2, $this->firstVCard->getProperties(Address::class));
        $this->assertCount(2, $this->firstVCard->getProperties(Email::class));
        $this->assertCount(1, $this->firstVCard->getProperties(Note::class));
        $this->assertCount(1, $this->firstVCard->getProperties(Birthdate::class));
        $this->assertCount(1, $this->firstVCard->getProperties(Anniversary::class));
        $this->assertCount(1, $this->firstVCard->getProperties(Telephone::class));

        $this->assertCount(2, $this->secondVCard->getProperties());
        $this->assertCount(1, $this->secondVCard->getProperties(Name::class));
        $this->assertCount(1, $this->secondVCard->getProperties(Address::class));

        $this->assertCount(4, $this->thirdVCard->getProperties());
        $this->assertCount(1, $this->thirdVCard->getProperties(Title::class));
        $this->assertCount(1, $this->thirdVCard->getProperties(Photo::class));
        $this->assertCount(1, $this->thirdVCard->getProperties(Logo::class));
        $this->assertCount(1, $this->thirdVCard->getProperties(Telephone::class));
    }
}
