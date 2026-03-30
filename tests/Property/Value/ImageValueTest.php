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

    public function testEmptyFile(): void
    {
        $this->expectException(\JeroenDesloovere\VCard\Exception\PropertyException::class);
        $this->expectExceptionMessage('The image you have provided is invalid.');
        new ImageValue(__DIR__ . '/../../assets/emptyfile');
    }

    public function testEmptyImage(): void
    {
        $this->expectException(\JeroenDesloovere\VCard\Exception\PropertyException::class);
        $this->expectExceptionMessage('The image you have provided is invalid.');
        new ImageValue(__DIR__ . '/../../assets/empty.jpg');
    }

    public function testWrongFile(): void
    {
        $this->expectException(\JeroenDesloovere\VCard\Exception\PropertyException::class);
        $this->expectExceptionMessage('The image you have provided is invalid.');
        new ImageValue(__DIR__ . '/../../assets/wrongfile');
    }
}
