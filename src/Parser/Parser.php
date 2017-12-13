<?php

namespace JeroenDesloovere\VCard\Parser;

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

    public static function getFileContents(string $fileName): string
    {
        if (!file_exists($fileName) || !is_readable($fileName)) {
            throw new \RuntimeException(sprintf("File %s is not readable, or doesn't exist.", $fileName));
        }

        return file_get_contents($fileName);
    }

    public function getVCards(): array
    {
        return $this->vCards;
    }
}
