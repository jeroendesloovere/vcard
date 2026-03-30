<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Formatter\Property\CalUriFormatter;
use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Parser\Property\CalUriParser;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Property\Parameter\Type;

final class CalUri implements PropertyInterface, NodeInterface
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
        return new CalUriFormatter($this);
    }

    public static function getNode(): string
    {
        return 'CALURI';
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public static function getParser(): NodeParserInterface
    {
        return new CalUriParser();
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
