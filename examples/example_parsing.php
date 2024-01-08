<?php

require_once __DIR__ . '/../vendor/autoload.php';

use JeroenDesloovere\VCard\VCardParser;

$pathToVCardExample = __DIR__ . '/assets/contacts.vcf';
$parser = VCardParser::parseFromFile($pathToVCardExample);

foreach($parser as $vcard) {
    $lastname = $vcard->lastname;
    $firstname = $vcard->firstname;
    $birthday = $vcard->getBirthday()->format('Y-m-d');
    
    printf("\"%s\",\"%s\",\"%s\"", $lastname, $firstname, $birthday);
    
    echo PHP_EOL;
}
