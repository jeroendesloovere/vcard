<?php

namespace JeroenDesloovere\VCard\Parser;

/**
 * Class Parser
 *
 * @package JeroenDesloovere\VCard\Parser
 */
class Parser
{
    /** @var ParserInterface */
    private $parser;

    /** @var array */
    private $vCards;

    /**
     * Parser constructor.
     *
     * @param ParserInterface $parser
     * @param string          $content
     */
    public function __construct(ParserInterface $parser, string $content)
    {
        $this->parser = $parser;
        $this->vCards = $this->parser->getVCards($content);
    }

    /**
     * @param string $fileName
     *
     * @return string
     * @throws \RuntimeException
     */
    public static function getFileContents(string $fileName): string
    {
        if (!file_exists($fileName) || !is_readable($fileName)) {
            throw new \RuntimeException(sprintf("File %s is not readable, or doesn't exist.", $fileName));
        }

        return file_get_contents($fileName);
    }

    /**
     * @return array
     */
    public function getVCards(): array
    {
        return $this->vCards;
    }
}
