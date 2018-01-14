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
     * @expectedExceptionMessage The property you are trying to add is empty.
     */
    public function testEmptyName(): void
    {
        new Name();
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\PropertyException
     * @expectedExceptionMessage The property you are trying to add is empty.
     */
    public function testEmptyAddress(): void
    {
        new Address();
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\PropertyException
     * @expectedExceptionMessage The property you are trying to add is empty.
     */
    public function testEmptyEmail(): void
    {
        new Email();
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\PropertyException
     * @expectedExceptionMessage The property you are trying to add is empty.
     */
    public function testEmptyGender(): void
    {
        new Gender();
    }
}
