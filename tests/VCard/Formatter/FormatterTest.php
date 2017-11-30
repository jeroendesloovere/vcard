<?php

namespace JeroenDesloovere\VCard\Formatter;

use JeroenDesloovere\VCard\Property\Address;
use JeroenDesloovere\VCard\Property\Name;
use JeroenDesloovere\VCard\PropertyParameter\Type;
use JeroenDesloovere\VCard\VCard;

require_once __DIR__ . '/../../../vendor/autoload.php';

class FormatterTest extends \PHPUnit_Framework_TestCase
{
    public function testVcfFormatter()
    {
        $vcfFormatter = new VcfFormatter();
        $formatter = new Formatter($vcfFormatter, 'export-multiple-vcf-cards');

        $vCard = new VCard();
        $vCard->add(new Name('John', 'Doe'));
        $vCard->add(new Address(null, 'Penthouse', 'Korenmarkt 1', 'Gent', 'Oost-Vlaanderen', '9000', 'BE', Type::work()));
        $formatter->addVCard($vCard);

        $formatter->save(__DIR__);
    }
}
