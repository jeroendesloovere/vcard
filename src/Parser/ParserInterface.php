<?php

namespace JeroenDesloovere\VCard\Parser;

use JeroenDesloovere\VCard\VCard;

/**
 * Interface ParserInterface
 *
 * @package JeroenDesloovere\VCard\Parser
 */
interface ParserInterface
{
    /**
     * @param string $content
     * @return VCard[]
     */
    public function getVCards(string $content): array;
}
