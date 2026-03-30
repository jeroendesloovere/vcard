<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Formatter\Property\FbUrlFormatter;
use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Parser\Property\FbUrlParser;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Property\Parameter\Type;

final class FbUrl implements PropertyInterface, NodeInterface
{
    /** @var string */
    private $url;

    /** @var Type */
    private $type;

    public function __construct(string $url, ?Type $type = null)
    {
        $this->url = $url;
        $this->type = $type ?? Type::home();
    }

    public function getFormatter(): NodeFormatterInterface
    {
        return new FbUrlFormatter($this);
    }

    public static function getNode(): string
    {
        return 'FBURL';
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public static function getParser(): NodeParserInterface
    {
        return new FbUrlParser();
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
