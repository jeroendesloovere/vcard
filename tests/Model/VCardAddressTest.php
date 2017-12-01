<?php

namespace JeroenDesloovere\VCard\Tests\Model;

use JeroenDesloovere\VCard\Model\VCardAddress;
use PHPUnit\Framework\TestCase;

/**
 * Class VCardAddressTest
 *
 * @package JeroenDesloovere\VCard\Tests\Model
 */
class VCardAddressTest extends TestCase
{
    /**
     *
     */
    public function testSetLabelGetLabel()
    {
        $vcardAddress = new VCardAddress();
        $vcardAddress->setLabel('Test label');
        $this->assertEquals('Test label', $vcardAddress->getLabel());
    }
}
