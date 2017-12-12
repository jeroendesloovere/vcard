<?php

/**
 * VCard generator test - can save to file or output as a download
 */

declare(strict_types=1);
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../src/VCard.php';
require_once __DIR__.'/../src/Formatter/Formatter.php';
require_once __DIR__.'/../src/Formatter/FormatterInterface.php';
require_once __DIR__.'/../src/Formatter/VcfFormatter.php';
require_once __DIR__.'/../src/Property/PropertyInterface.php';
require_once __DIR__.'/../src/Property/Name.php';
require_once __DIR__.'/../src/Property/Address.php';
require_once __DIR__.'/../src/Property/Parameter/Type.php';
require_once __DIR__.'/../src/Property/Parameter/Kind.php';

use JeroenDesloovere\VCard\VCard;
use JeroenDesloovere\VCard\Formatter\Formatter;
use JeroenDesloovere\VCard\Formatter\VcfFormatter;
use JeroenDesloovere\VCard\Property\Name;
use JeroenDesloovere\VCard\Property\Address;
use JeroenDesloovere\VCard\Property\Parameter\Type;

// Step one: build one or more vCards
$vCard = new VCard();
$vCard->add(new Name('Desloovere', 'Jeroen'));
$vCard->add(new Address(null, 'Penthouse', 'Korenmarkt 1', 'Gent', 'Oost-Vlaanderen', '9000', 'BE', Type::work()));

// Step two: use the VcfFormatter to create a .vcf file (which can contain multiple vCards)
$vcf = new Formatter(new VcfFormatter(), 'example');
$vcf->addVCard($vCard);

// Step three: "download", "save" or custom method.
$vcf->download();

// Or save to a file
$vcf->save(__DIR__);

// Or get content and headers for use in your framework
$vcf->getContent();
$vcf->getHeaders();

/*

// add work data
$vcard->addCompany('Siesqo');
$vcard->addJobtitle('Web Developer');
$vcard->addEmail('info@jeroendesloovere.be');
$vcard->addPhoneNumber(1234121212, 'PREF;WORK');
$vcard->addPhoneNumber(123456789, 'WORK');
$vcard->addAddress(null, null, 'street', 'worktown', null, 'workpostcode', 'Belgium');
$vcard->addURL('http://www.jeroendesloovere.be');

$vcard->addPhoto(__DIR__ . '/assets/landscape.jpeg');
//$vcard->addPhoto('https://raw.githubusercontent.com/jeroendesloovere/vcard/master/tests/image.jpg');

// return vcard as a string
//return $vcard->getOutput();

// return vcard as a download
return $vcard->download();

// echo message
echo 'A personal vCard is saved in this folder: ' . __DIR__;

// or

// save the card in file in the current folder
// return $vcard->save();

// echo message
// echo 'A personal vCard is saved in this folder: ' . __DIR__;
*/