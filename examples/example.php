<?php

/**
 * VCard generator test - can save to file or output as a download
 *
 * @author Jeroen Desloovere <jeroen@siesqo.be>
 */

require_once __DIR__ . '/../src/JeroenDesloovere/VCard/VCard.php';

use JeroenDesloovere\VCard\VCard;

// define vcard
$vcard = new VCard();

// define variables
$firstname = 'Jeroen';
$lastname = 'Desloovere';

// add personal data
$vcard->addName($lastname, $firstname);

// add work data
$vcard->addCompany('Siesqo');
$vcard->addJobtitle('Web Developer');
$vcard->addEmail('jeroen@siesqo.be');
$vcard->addPhoneNumber(1234121212, 'PREF;WORK');
$vcard->addPhoneNumber(123456789, 'WORK');
$vcard->addAddress(null, null, 'street', 'worktown', null, 'workpostcode', 'Belgium');
$vcard->addURL('http://www.siesqo.be');

// return vcard as a download
return $vcard->download();

// echo message
echo 'A personal vCard is saved in this folder: ' . __DIR__;

// or

// save the card in file in the current folder
// return $vcard->save();

// echo message
// echo 'A personal vCard is saved in this folder: ' . __DIR__;
