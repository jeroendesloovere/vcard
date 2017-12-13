<?php

namespace JeroenDesloovere\VCard\Parser;

class Parser
{
    /** @var ParserInterface */
    private $parser;

    /** @var array */
    private $vCards = [];

    public function __construct(ParserInterface $parser, string $content)
    {
        $this->parser = $parser;
        $this->vCards = $this->parser->getVCards($content);
    }

    public static function getFileContents(string $fileName): string
    {
        if (file_exists($fileName) && is_readable($fileName)) {
            return file_get_contents($fileName);
        } else {
            throw new \RuntimeException(sprintf("File %s is not readable, or doesn't exist.", $fileName));
        }
    }

    public function getVCards(): array
    {
        return $this->vCards;
    }
}
