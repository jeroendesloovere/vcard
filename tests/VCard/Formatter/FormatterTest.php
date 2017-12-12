<?php

namespace JeroenDesloovere\VCard\Formatter;

use JeroenDesloovere\VCard\Property\Address;
use JeroenDesloovere\VCard\Property\Name;
use JeroenDesloovere\VCard\Property\Parameter\Type;
use JeroenDesloovere\VCard\VCard;

require_once __DIR__ . '/../../../vendor/autoload.php';

class FormatterTest extends \PHPUnit_Framework_TestCase
{
    public function testVcfFormatter()
    {
        // Building one or multiple vCards
        $firstVCard = (new VCard())
            ->add(new Name('Jeroen', 'Desloovere'))
            ->add(new Address(null, null, 'Markt 1', 'Brugge', 'West-Vlaanderen', '8000', 'België', Type::work()));
        $secondVCard = (new VCard())
            ->add(new Name('John', 'Doe'))
            ->add(new Address(null, 'Penthouse', 'Korenmarkt 1', 'Gent', 'Oost-Vlaanderen', '9000', 'België', Type::work()));

        // Saving to .vcf file
        $formatter = new Formatter(new VcfFormatter(), 'export-multiple-vcf-cards');
        $formatter->addVCard($firstVCard);
        $formatter->addVCard($secondVCard);
        $formatter->save(__DIR__);
    }
}
