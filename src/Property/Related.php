<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Formatter\Property\RelatedFormatter;
use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Parser\Property\RelatedParser;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Property\Parameter\Type;

final class Related implements PropertyInterface, NodeInterface
{
    /** @var string */
    private $uri;

    /** @var Type */
    private $type;

    public function __construct(string $uri, ?Type $type = null)
    {
        $this->uri = $uri;
        $this->type = $type ?? Type::home();
    }

    public function getFormatter(): NodeFormatterInterface
    {
        return new RelatedFormatter($this);
    }

    public static function getNode(): string
    {
        return 'RELATED';
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public static function getParser(): NodeParserInterface
    {
        return new RelatedParser();
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
