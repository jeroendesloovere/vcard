<?php

declare(strict_types=1);

namespace Dilone\VCard\Parser;

use Dilone\VCard\Exception\ParserException;
use Dilone\VCard\VCard;

final class Parser
{
    /** @var ParserInterface */
    private $parser;

    /** @var VCard[] */
    private $vCards;

    public function __construct(ParserInterface $parser, string $content)
    {
        $this->parser = $parser;
        $this->vCards = $this->parser->getVCards($content);
    }

    /**
     * @param string $file
     * @return string
     * @throws ParserException
     */
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
