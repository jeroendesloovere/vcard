<?php

declare(strict_types=1);

namespace JeroenDesloovere\Tests\VCard\Property\Value;

use JeroenDesloovere\VCard\Property\Value\ImageValue;
use PHPUnit\Framework\TestCase;

/**
 * How to execute all tests: `vendor/bin/phpunit tests`
 */
final class ImageValueTest extends TestCase
{
    public function testValidLocalImageUrl(): void
    {
        $image = new ImageValue(__DIR__ . '/../../assets/landscape.jpeg');
        $this->assertTrue($image instanceof ImageValue);
    }

    public function testValidImageContent(): void
    {
        $image = new ImageValue(file_get_contents(__DIR__ . '/../../assets/landscape.jpeg'));
        $this->assertTrue($image instanceof ImageValue);
    }

    public function testValidImageUrl(): void
    {
        $image = new ImageValue('http://www.jeroendesloovere.be/images/my_head.jpg');
        $this->assertTrue($image instanceof ImageValue);
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\PropertyException
     * @expectedExceptionMessage The image you have provided is invalid.
     */
    public function testEmptyFile(): void
    {
        new ImageValue(__DIR__ . '/../../assets/emptyfile');
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\PropertyException
     * @expectedExceptionMessage The image you have provided is invalid.
     */
    public function testEmptyImage(): void
    {
        new ImageValue(__DIR__ . '/../../assets/empty.jpg');
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\PropertyException
     * @expectedExceptionMessage The image you have provided is invalid.
     */
    public function testWrongFile(): void
    {
        new ImageValue(__DIR__ . '/../../assets/wrongfile');
    }
}
