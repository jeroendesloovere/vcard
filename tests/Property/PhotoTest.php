<?php

namespace JeroenDesloovere\Tests\VCard\Property\Value;

use JeroenDesloovere\VCard\Property\Photo;
use PHPUnit\Framework\TestCase;

/**
 * How to execute all tests: `vendor/bin/phpunit tests`
 */
final class PhotoTest extends TestCase
{
    public function testValidLocalImageUrl(): void
    {
        $image = new Photo(__DIR__ . '/../assets/landscape.jpeg');
        $this->assertTrue($image instanceof Photo);
    }

    public function testValidImageContent(): void
    {
        $image = new Photo(file_get_contents(__DIR__ . '/../assets/landscape.jpeg'));
        $this->assertTrue($image instanceof Photo);
    }

    public function testValidImageUrl(): void
    {
        $image = new Photo('http://www.jeroendesloovere.be/images/my_head.jpg');
        $this->assertTrue($image instanceof Photo);
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\PropertyException
     * @expectedExceptionMessage The image you have provided is invalid.
     */
    public function testEmptyFile(): void
    {
        new Photo(__DIR__ . '/../assets/emptyfile');
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\PropertyException
     * @expectedExceptionMessage The image you have provided is invalid.
     */
    public function testEmptyImage(): void
    {
        new Photo(__DIR__ . '/../assets/empty.jpg');
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\PropertyException
     * @expectedExceptionMessage The image you have provided is invalid.
     */
    public function testWrongFile(): void
    {
        new Photo(__DIR__ . '/../assets/wrongfile');
    }
}
