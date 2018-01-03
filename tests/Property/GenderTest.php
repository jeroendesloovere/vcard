<?php

namespace JeroenDesloovere\VCard\Property;

use PHPUnit\Framework\TestCase;

/**
 * How to execute all tests: `vendor/bin/phpunit tests`
 */
final class GenderTest extends TestCase
{
    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\PropertyException
     * @expectedExceptionMessage The given value 'False Gender' is not allowed. Possible values are: 'Female', 'Male', 'None', 'Other', 'Unknown'
     */
    public function testGenderFalseGender(): void
    {
        new Gender('False Gender');
    }

    public function testGenderEmpty(): void
    {
        $gender = new Gender('');

        $this->assertTrue($gender->isNone());
        $this->assertEquals('None', $gender->__toString());
    }

    public function testGenderFemale(): void
    {
        $gender = new Gender('Female');

        $this->assertTrue($gender->isFemale());
        $this->assertEquals('Female', $gender->__toString());
    }

    public function testGenderMale(): void
    {
        $gender = new Gender('Male');

        $this->assertTrue($gender->isMale());
        $this->assertEquals('Male', $gender->__toString());
    }

    public function testGenderNone(): void
    {
        $gender = new Gender('');

        $this->assertTrue($gender->isNone());
        $this->assertEquals('None', $gender->__toString());
    }

    public function testGenderOther(): void
    {
        $gender = new Gender('Other');

        $this->assertTrue($gender->isOther());
        $this->assertEquals('Other', $gender->__toString());
    }

    public function testGenderUnknown(): void
    {
        $gender = new Gender('Unknown');

        $this->assertTrue($gender->isUnknown());
        $this->assertEquals('Unknown', $gender->__toString());
    }

    public function testGenderFemaleFunction(): void
    {
        $gender = Gender::female();

        $this->assertTrue($gender->isFemale());
        $this->assertEquals('Female', $gender->__toString());
    }

    public function testGenderMaleFunction(): void
    {
        $gender = Gender::male();

        $this->assertTrue($gender->isMale());
        $this->assertEquals('Male', $gender->__toString());
    }

    public function testGenderNoneFunction(): void
    {
        $gender = Gender::none();

        $this->assertTrue($gender->isNone());
        $this->assertEquals('None', $gender->__toString());
    }

    public function testGenderOtherFunction(): void
    {
        $gender = Gender::other();

        $this->assertTrue($gender->isOther());
        $this->assertEquals('Other', $gender->__toString());
    }

    public function testGenderUnknownFunction(): void
    {
        $gender = Gender::unknown();

        $this->assertTrue($gender->isUnknown());
        $this->assertEquals('Unknown', $gender->__toString());
    }
}
