<?php

declare(strict_types=1);

namespace SEEC\VCard\Tests;

use PHPUnit\Framework\TestCase;
use SEEC\VCard\VCardException;

final class VCardExceptionTest extends TestCase
{
    public function testException(): never
    {
        $this->expectException(VCardException::class);

        throw new VCardException('Testing the VCard error.');
    }
}
