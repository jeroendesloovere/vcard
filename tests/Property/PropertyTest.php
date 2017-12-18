<?php

namespace JeroenDesloovere\VCard\Property;

use PHPUnit\Framework\TestCase;

/**
 * How to execute all tests: `vendor/bin/phpunit tests`
 */
final class PropertyTest extends TestCase
{
    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\PropertyException
     */
    public function testEmptyName(): void
    {
        new Name();
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\PropertyException
     */
    public function testEmptyAddress(): void
    {
        new Address();
    }
}
