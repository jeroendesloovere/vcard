<?php

namespace JeroenDesloovere\VCard\Parser;

class Parser
{
    /** @var ParserInterface */
    private $formatter;

    /** @var array */
    private $vCards = [];

    public function __construct(ParserInterface $formatter, string $content)
    {
        $this->formatter = $formatter;
        $this->vCards = $this->formatter->getVCards($content);
    }

    public static function getContentFromFile(string $fileName): string
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
