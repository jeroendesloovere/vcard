<?php

namespace JeroenDesloovere\VCard\Formatter;

use JeroenDesloovere\VCard\Parser\Parser;
use JeroenDesloovere\VCard\Parser\VcfParser;
use JeroenDesloovere\VCard\Property\Address;
use JeroenDesloovere\VCard\Property\Name;
use JeroenDesloovere\VCard\Property\Parameter\Type;
use JeroenDesloovere\VCard\VCard;

require_once __DIR__ . '/../../../vendor/autoload.php';

class FormatterTest extends \PHPUnit_Framework_TestCase
{
    /** @var VCard */
    private $firstVCard;

    /** @var VCard */
    private $secondVCard;

    public function setUp()
    {
        // Building one or multiple vCards
        $this->firstVCard = (new VCard())
            ->add(new Name('Jeroen', 'Desloovere'))
            ->add(new Address(null, null, 'Markt 1', 'Brugge', 'West-Vlaanderen', '8000', 'België', Type::work()));
        $this->secondVCard = (new VCard())
            ->add(new Name('John', 'Doe'))
            ->add(new Address(null, 'Penthouse', 'Korenmarkt 1', 'Gent', 'Oost-Vlaanderen', '9000', 'België', Type::work()));
    }

    public function testSavingOneVCardToVcfFile()
    {
        // Saving "vcard.vcf"
        $formatter = new Formatter(new VcfFormatter(), 'vcard');
        $formatter->addVCard($this->firstVCard);
        $formatter->save(__DIR__ . '/../../');
    }

    public function testSavingMultipleVCardsToVcfFile()
    {
        // Saving "vcards.vcf"
        $formatter = new Formatter(new VcfFormatter(), 'vcards');
        $formatter->addVCard($this->firstVCard);
        $formatter->addVCard($this->secondVCard);
        $formatter->save(__DIR__ . '/../../');
    }

    public function testParsingOneVCardFromVcfFile()
    {
        $parser = new Parser(new VcfParser(), Parser::getFileContents(__DIR__ . '/../../vcard.vcf'));

        // @todo
        //$this->assertEquals($this->firstVCard, $parser->getVCards()[0]);
    }

    public function testParsingMultipleVCardsFromVcfFile()
    {
        $parser = new Parser(new VcfParser(), Parser::getFileContents(__DIR__ . '/../../vcards.vcf'));

        // @todo
        //$this->assertEquals($this->firstVCard, $parser->getVCards()[0]);
        //$this->assertEquals($this->secondVCard, $parser->getVCards()[1]);
    }
}
