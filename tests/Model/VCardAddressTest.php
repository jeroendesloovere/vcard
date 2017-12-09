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

    /**
     * @throws \JeroenDesloovere\VCard\Exception\InvalidVersionException
     */
    public function testParser30()
    {
        $vcardAddress = new VCardAddress();
        $vcardAddress->parser('3.0', 'test 3.0', 'parser name;parser extended;parser street;parser locality;parser region;parser postalCode;parser country');
        $this->assertEquals('test 3.0', $vcardAddress->getLabel());
    }

    /**
     * @throws \JeroenDesloovere\VCard\Exception\InvalidVersionException
     */
    public function testParser40()
    {
        $vcardAddress = new VCardAddress();
        $vcardAddress->parser('4.0', 'LABEL=test 4.0', 'parser name;parser extended;parser street;parser locality;parser region;parser postalCode;parser country');
        $this->assertEquals('LABEL=test 4.0', $vcardAddress->getLabel());
    }

    /**
     * @throws \JeroenDesloovere\VCard\Exception\InvalidVersionException
     */
    public function testParser40NoLabel()
    {
        $vcardAddress = new VCardAddress();
        $vcardAddress->parser('4.0', 'test 4.0', 'parser name;parser extended;parser street;parser locality;parser region;parser postalCode;parser country');
        $this->assertEquals(null, $vcardAddress->getLabel());
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\InvalidVersionException
     * @expectedExceptionMessage Invalid VCard version.
     *
     * @throws \JeroenDesloovere\VCard\Exception\InvalidVersionException
     */
    public function testParserException()
    {
        $vcardAddress = new VCardAddress();
        $vcardAddress->parser('exception version', 'test exception', 'parser name;parser extended;parser street;parser locality;parser region;parser postalCode;parser country');
    }
}
