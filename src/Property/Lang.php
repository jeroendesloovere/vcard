<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Formatter\Property\LangFormatter;
use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Parser\Property\LangParser;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Property\Parameter\Type;

final class Lang implements PropertyInterface, NodeInterface
{
    /** @var string */
    private $language;

    /** @var Type */
    private $type;

    public function __construct(string $language, ?Type $type = null)
    {
        $this->language = $language;
        $this->type = $type ?? Type::home();
    }

    public function getFormatter(): NodeFormatterInterface
    {
        return new LangFormatter($this);
    }

    public static function getNode(): string
    {
        return 'LANG';
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public static function getParser(): NodeParserInterface
    {
        return new LangParser();
    }

    public function isAllowedMultipleTimes(): bool
    {
        return true;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function setType(Type $type): void
    {
        $this->type = $type;
    }
}
