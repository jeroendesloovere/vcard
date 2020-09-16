<?php

declare(strict_types=1);

namespace Dilone\Tests\VCard\Property;

use Dilone\VCard\Property\Address;
use Dilone\VCard\Property\Email;
use Dilone\VCard\Property\Gender;
use Dilone\VCard\Property\Name;
use PHPUnit\Framework\TestCase;

/**
 * How to execute all tests: `vendor/bin/phpunit tests`
 */
final class PropertyTest extends TestCase
{
    /**
     * @expectedException \Dilone\VCard\Exception\PropertyException
     * @expectedExceptionMessage The property you are trying to add is empty.
     */
    public function testEmptyName(): void
    {
        new Name();
    }

    /**
     * @expectedException \Dilone\VCard\Exception\PropertyException
     * @expectedExceptionMessage The property you are trying to add is empty.
     */
    public function testEmptyAddress(): void
    {
        new Address();
    }

    /**
     * @expectedException \Dilone\VCard\Exception\PropertyException
     * @expectedExceptionMessage The property you are trying to add is empty.
     */
    public function testEmptyEmail(): void
    {
        new Email();
    }

    /**
     * @expectedException \Dilone\VCard\Exception\PropertyException
     * @expectedExceptionMessage The property you are trying to add is empty.
     */
    public function testEmptyGender(): void
    {
        new Gender();
    }
}
