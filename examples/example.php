<?php

/**
 * VCard generator test - can save to file or output as a download
 */

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../src/VCardBuilder.php';
require_once __DIR__.'/../src/Model/VCard.php';
require_once __DIR__.'/../src/Model/VCardAddress.php';

use JeroenDesloovere\VCard\Model\VCard;
use JeroenDesloovere\VCard\Model\VCardAddress;
use JeroenDesloovere\VCard\VCardBuilder;

// define vcard
$vcard = new VCard();

// define variables
$prefix = '';
$firstname = 'Jeroen';
$additional = '';
$lastname = 'Desloovere';
$suffix = '';

// add personal data
$vcard->setPrefix($prefix);
$vcard->setFirstName($firstname);
$vcard->setAdditional($additional);
$vcard->setLastName($lastname);
$vcard->setSuffix($suffix);

// add work data
$vcard->setOrganization('Siesqo');
$vcard->setTitle('Web Developer');
$vcard->addEmail('info@jeroendesloovere.be');
$vcard->addPhone(1234121212, 'PREF;WORK');
$vcard->addPhone(123456789, 'WORK');

$vcardAddress = new VCardAddress();
$vcardAddress->setName(null);
$vcardAddress->setExtended(null);
$vcardAddress->setStreet('street');
$vcardAddress->setLocality('worktown');
$vcardAddress->setRegion(null);
$vcardAddress->setPostalCode('workpostcode');
$vcardAddress->setCountry('Belgium');
$vcard->addAddress($vcardAddress);

$vcard->addUrl('http://www.jeroendesloovere.be');

$vcard->setPhoto(__DIR__.'/assets/landscape.jpeg');
//$vcard->setPhoto('https://raw.githubusercontent.com/jeroendesloovere/vcard/master/tests/image.jpg');

$vcardBuilder = new VCardBuilder($vcard);

// return vcard as a string
//return $vcardBuilder->getOutput();

// return vcard as a download
$vcardBuilder->download();

// echo message
echo 'A personal vCard is saved in this folder: '.__DIR__;

// or

// save the card in file in the current folder
// return $vcardBuilder->save();

// echo message
// echo 'A personal vCard is saved in this folder: ' . __DIR__;
