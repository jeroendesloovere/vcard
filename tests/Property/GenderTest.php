<?php

declare(strict_types=1);

namespace JeroenDesloovere\Tests\VCard\Property;

use JeroenDesloovere\VCard\Property\Gender;
use PHPUnit\Framework\TestCase;

/**
 * How to execute all tests: `vendor/bin/phpunit tests`
 */
final class GenderTest extends TestCase
{
    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\PropertyException
     * @expectedExceptionMessage The given value "False Gender" is not allowed.
     * Possible values are: "", "F", "M", "N", "O", "U"
     */
    public function testGenderFalseGender(): void
    {
        new Gender('False Gender');
    }

    public function testGenderFemale(): void
    {
        $gender = new Gender('F');

        $this->assertTrue($gender->isFemale());
        $this->assertEquals('F', $gender->__toString());
    }

    public function testGenderMale(): void
    {
        $gender = new Gender('M');

        $this->assertTrue($gender->isMale());
        $this->assertEquals('M', $gender->__toString());
    }

    public function testGenderNone(): void
    {
        $gender = new Gender('N');

        $this->assertTrue($gender->isNone());
        $this->assertEquals('N', $gender->__toString());
    }

    public function testGenderOther(): void
    {
        $gender = new Gender('O');

        $this->assertTrue($gender->isOther());
        $this->assertEquals('O', $gender->__toString());
    }

    public function testGenderUnknown(): void
    {
        $gender = new Gender('U');

        $this->assertTrue($gender->isUnknown());
        $this->assertEquals('U', $gender->__toString());
    }

    public function testGenderEmptyFunction(): void
    {
        $gender = Gender::empty('It\'s complicated');

        $this->assertTrue($gender->isEmpty());
        $this->assertEquals('', $gender->getValue());
    }

    public function testGenderFemaleFunction(): void
    {
        $gender = Gender::female();

        $this->assertTrue($gender->isFemale());
        $this->assertEquals('F', $gender->__toString());
    }

    public function testGenderMaleFunction(): void
    {
        $gender = Gender::male();

        $this->assertTrue($gender->isMale());
        $this->assertEquals('M', $gender->__toString());
    }

    public function testGenderNoneFunction(): void
    {
        $gender = Gender::none();

        $this->assertTrue($gender->isNone());
        $this->assertEquals('N', $gender->__toString());
    }

    public function testGenderOtherFunction(): void
    {
        $gender = Gender::other();

        $this->assertTrue($gender->isOther());
        $this->assertEquals('O', $gender->__toString());
    }

    public function testGenderUnknownFunction(): void
    {
        $gender = Gender::unknown();

        $this->assertTrue($gender->isUnknown());
        $this->assertEquals('U', $gender->__toString());
    }
}
