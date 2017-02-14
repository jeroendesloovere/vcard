<?php

/**
 * VCardParser test - can parse bundled VCF file into CSV
 *
 * @author Md. Minhazul Haque <mdminhazulhaque@gmail.com>
 */

require_once __DIR__.'/vendor/autoload.php';

// load VCardParser classes
use JeroenDesloovere\VCard\VCardParser;

$SOURCE_VCF_FILENAME = 'contacts.vcf';
$parser = VCardParser::parseFromFile($SOURCE_VCF_FILENAME);

foreach($parser as $vcard) {
    $lastname = $vcard->lastname;
    $firstname = $vcard->firstname;
    $birthday = $vcard->birthday->format('Y-m-d');
    
    printf("\"%s\",\"%s\",\"%s\"", $lastname, $firstname, $birthday);
    
    echo PHP_EOL;
}
