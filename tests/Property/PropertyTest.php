<?php

declare(strict_types=1);

namespace JeroenDesloovere\Tests\VCard\Property;

use JeroenDesloovere\VCard\Property\Address;
use JeroenDesloovere\VCard\Property\Email;
use JeroenDesloovere\VCard\Property\Gender;
use JeroenDesloovere\VCard\Property\Name;
use PHPUnit\Framework\TestCase;

/**
 * How to execute all tests: `vendor/bin/phpunit tests`
 */
final class PropertyTest extends TestCase
{
    public function testEmptyName(): void
    {
        $this->expectException(\JeroenDesloovere\VCard\Exception\PropertyException::class);
        $this->expectExceptionMessage('The property you are trying to add is empty.');
        new Name();
    }

    public function testEmptyAddress(): void
    {
        $this->expectException(\JeroenDesloovere\VCard\Exception\PropertyException::class);
        $this->expectExceptionMessage('The property you are trying to add is empty.');
        new Address();
    }

    public function testEmptyEmail(): void
    {
        $this->expectException(\JeroenDesloovere\VCard\Exception\PropertyException::class);
        $this->expectExceptionMessage('The property you are trying to add is empty.');
        new Email();
    }

    public function testEmptyGender(): void
    {
        $this->expectException(\JeroenDesloovere\VCard\Exception\PropertyException::class);
        $this->expectExceptionMessage('The property you are trying to add is empty.');
        new Gender();
    }
}
