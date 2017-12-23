<?php

namespace JeroenDesloovere\VCard\Parser;

use JeroenDesloovere\VCard\Exception\ParserException;
use JeroenDesloovere\VCard\VCard;

final class Parser
{
    /**
     * @var ParserInterface
     */
    private $parser;

    /**
     * @var VCard[]
     */
    private $vCards;

    public function __construct(ParserInterface $parser, string $content)
    {
        $this->parser = $parser;
        $this->vCards = $this->parser->getVCards($content);
    }

    public static function getFileContents(string $file): string
    {
        if (!file_exists($file) || !is_readable($file)) {
            throw ParserException::forUnreadableVCard($file);
        }

        $contents = file_get_contents($file);

        return ($contents !== false) ? $contents : '';
    }

    /**
     * @return VCard[]
     */
    public function getVCards(): array
    {
        return $this->vCards;
    }
}
