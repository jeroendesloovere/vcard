<?php

namespace JeroenDesloovere\VCard\Property\Parameter;

use PHPUnit\Framework\TestCase;

/**
 * How to execute all tests: `vendor/bin/phpunit tests`
 */
final class GenderTypeTest extends TestCase
{
    public function testGenderTypeEmpty(): void
    {
        $genderType = new GenderType('');

        $this->assertTrue($genderType->isEmpty());
        $this->assertEquals('', $genderType->__toString());
    }

    public function testGenderTypeMale(): void
    {
        $genderType = new GenderType('M');

        $this->assertTrue($genderType->isMale());
        $this->assertEquals('M', $genderType->__toString());
    }

    public function testGenderTypeFemale(): void
    {
        $genderType = new GenderType('F');

        $this->assertTrue($genderType->isFemale());
        $this->assertEquals('F', $genderType->__toString());
    }

    public function testGenderTypeOther(): void
    {
        $genderType = new GenderType('O');

        $this->assertTrue($genderType->isOther());
        $this->assertEquals('O', $genderType->__toString());
    }

    public function testGenderTypeNone(): void
    {
        $genderType = new GenderType('N');

        $this->assertTrue($genderType->isNone());
        $this->assertEquals('N', $genderType->__toString());
    }

    public function testGenderTypeUnknown(): void
    {
        $genderType = new GenderType('U');

        $this->assertTrue($genderType->isUnknown());
        $this->assertEquals('U', $genderType->__toString());
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\PropertyParameterException
     * @expectedExceptionMessage The given value 'False Gender' is not allowed. Possible values are: '', 'M', 'F', 'O', 'N', 'U'
     */
    public function testGenderTypeFalseGender(): void
    {
        new GenderType('False Gender');
    }

    public function testGenderTypeEmptyFunction(): void
    {
        $genderType = GenderType::empty();

        $this->assertTrue($genderType->isEmpty());
        $this->assertEquals('', $genderType->__toString());
    }

    public function testGenderTypeMaleFunction(): void
    {
        $genderType = GenderType::male();

        $this->assertTrue($genderType->isMale());
        $this->assertEquals('M', $genderType->__toString());
    }

    public function testGenderTypeFemaleFunction(): void
    {
        $genderType = GenderType::female();

        $this->assertTrue($genderType->isFemale());
        $this->assertEquals('F', $genderType->__toString());
    }

    public function testGenderTypeOtherFunction(): void
    {
        $genderType = GenderType::other();

        $this->assertTrue($genderType->isOther());
        $this->assertEquals('O', $genderType->__toString());
    }

    public function testGenderTypeNoneFunction(): void
    {
        $genderType = GenderType::none();

        $this->assertTrue($genderType->isNone());
        $this->assertEquals('N', $genderType->__toString());
    }

    public function testGenderTypeUnknownFunction(): void
    {
        $genderType = GenderType::unknown();

        $this->assertTrue($genderType->isUnknown());
        $this->assertEquals('U', $genderType->__toString());
    }
}
