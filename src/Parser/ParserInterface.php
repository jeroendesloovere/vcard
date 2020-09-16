<?php

declare(strict_types=1);

namespace Dilone\VCard\Parser;

use Dilone\VCard\VCard;

interface ParserInterface
{
    /**
     * @param string $content
     * @return VCard[]
     */
    public function getVCards(string $content): array;
}
