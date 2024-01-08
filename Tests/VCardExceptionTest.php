<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard;

use PHPUnit\Framework\TestCase;

final class VCardExceptionTest extends TestCase
{
    public function testException(): void
    {
        $this->expectException(VCardException::class);

        throw new VCardException('Testing the VCard error.');
    }
}
