<?php

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\VCard;
use PHPUnit\Framework\TestCase;

/**
 * How to execute all tests: `vendor/bin/phpunit tests`
 */
class PropertyTest extends TestCase
{
    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\PropertyException
     */
    public function testEmptyName(): void
    {
        (new VCard())->add(new Name());
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\PropertyException
     */
    public function testEmptyAddress(): void
    {
        (new VCard())->add(new Address());
    }
}
