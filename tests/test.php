<?php

/**
 * VCard generator test - can save to file or output as a download
 *
 * @author Jeroen Desloovere <jeroen@siesqo.be>
 */

// autoload files using Composer autoload
require_once __DIR__ . '/../vendor/autoload.php';

use JeroenDesloovere\VCard;

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
$vcard->addPhoneNumber(0000112233, 'PREF;WORK');
$vcard->addPhoneNumber(000112233, 'WORK');
$vcard->addAddress(null, null, 'street', 'worktown', null, 'workpostcode', 'Belgium');
$vcard->addURL('http://www.siesqo.be');

// echo message
echo "A personal vCard will be downloaded.";

// return vcard as a download
return $vcard->download();

// or you can save the card
// return $vcard->save();
