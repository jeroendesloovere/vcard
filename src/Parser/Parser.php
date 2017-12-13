<?php

namespace JeroenDesloovere\VCard\Parser;

use JeroenDesloovere\VCard\Exception\VCardException;
use JeroenDesloovere\VCard\VCard;

class Parser
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
            throw VCardException::forUnreadableFile($file);
        }

        $contents = file_get_contents($file);

        return ($contents !== false) ? $contents : '';
    }

    public function getVCards(): array
    {
        return $this->vCards;
    }
}
