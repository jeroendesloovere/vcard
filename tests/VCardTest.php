<?php

namespace JeroenDesloovere\VCard;

use JeroenDesloovere\VCard\Formatter\Formatter;
use JeroenDesloovere\VCard\Formatter\VcfFormatter;
use JeroenDesloovere\VCard\Parser\Parser;
use JeroenDesloovere\VCard\Parser\VcfParser;
use JeroenDesloovere\VCard\Property\Address;
use JeroenDesloovere\VCard\Property\Gender;
use JeroenDesloovere\VCard\Property\Name;
use JeroenDesloovere\VCard\Property\Note;
use JeroenDesloovere\VCard\Property\Parameter\GenderType;
use JeroenDesloovere\VCard\Property\Parameter\Type;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

/**
 * How to execute all tests: `vendor/bin/phpunit tests`
 */
final class VCardTest extends TestCase
{
    /**
     * @var vfsStreamDirectory
     */
    private $vfsRoot;

    /**
     * @var VCard 
     */
    private $firstVCard;

    /**
     * @var VCard 
     */
    private $secondVCard;

    public function setUp(): void
    {
        $this->vfsRoot = vfsStream::setup();

        // Building one or multiple vCards
        $this->firstVCard = (new VCard())
            ->add(new Name('Desloovere', 'Jeroen'))
            ->add(new Address(null, null, 'Markt 1', 'Brugge', 'West-Vlaanderen', '8000', 'België', Type::work()))
            ->add(new Address(null, 'Penthouse', 'Korenmarkt 1', 'Gent', 'Oost-Vlaanderen', '9000', 'België', Type::home()))
            ->add(new Note('VCard library is amazing.'))
            ->add(new Gender(GenderType::male(), 'test comment gender'));

        $this->secondVCard = (new VCard())
            ->add(new Name('Doe', 'John'))
            ->add(new Address(null, 'Penthouse', 'Korenmarkt 1', 'Gent', 'Oost-Vlaanderen', '9000', 'België', Type::work()));
    }

    public function testFormatterSavingMultipleVCardsToVcfFile(): void
    {
        // Saving "vcards.vcf"
        $formatter = new Formatter(new VcfFormatter(), 'vcards');
        $formatter->addVCard($this->firstVCard);
        $formatter->addVCard($this->secondVCard);

        $this->assertFalse($this->vfsRoot->hasChild('vcards.vcf'));
        $formatter->save($this->vfsRoot->url());
        $this->assertTrue($this->vfsRoot->hasChild('vcards.vcf'));
    }

    public function testFormatterSavingOneVCardToVcfFile(): void
    {
        // Saving "vcard.vcf"
        $formatter = new Formatter(new VcfFormatter(), 'vcard');
        $formatter->addVCard($this->firstVCard);

        $this->assertFalse($this->vfsRoot->hasChild('vcard.vcf'));
        $formatter->save($this->vfsRoot->url());
        $this->assertTrue($this->vfsRoot->hasChild('vcard.vcf'));
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\ParserException
     * @expectedExceptionMessage File 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.' is not readable, or doesn't exist.
     */
    public function testParserCorruptVCard(): void
    {
        new Parser(new VcfParser(), 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\ParserException
     * @expectedExceptionMessage File '' is not readable, or doesn't exist.
     */
    public function testParserEmptyVCard(): void
    {
        new Parser(new VcfParser(), '');
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
        $this->assertCount(5, $this->firstVCard->getProperties());
        $this->assertCount(1, $this->firstVCard->getProperties(Name::class));
        $this->assertCount(2, $this->firstVCard->getProperties(Address::class));
        $this->assertCount(1, $this->firstVCard->getProperties(Note::class));
        $this->assertCount(1, $this->firstVCard->getProperties(Gender::class));
        $this->assertCount(2, $this->secondVCard->getProperties());
        $this->assertCount(1, $this->secondVCard->getProperties(Name::class));
        $this->assertCount(1, $this->secondVCard->getProperties(Address::class));
    }
}
