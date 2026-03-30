<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Formatter\Property\OrgFormatter;
use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Parser\Property\OrgParser;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;

final class Org implements PropertyInterface, NodeInterface
{
    /** @var string */
    private $organizationName;

    /** @var string[] */
    private $units;

    public function __construct(string $organizationName, string ...$units)
    {
        $this->organizationName = $organizationName;
        $this->units = $units;
    }

    public function getFormatter(): NodeFormatterInterface
    {
        return new OrgFormatter($this);
    }

    public static function getNode(): string
    {
        return 'ORG';
    }

    public function getOrganizationName(): string
    {
        return $this->organizationName;
    }

    public function getUnits(): array
    {
        return $this->units;
    }

    public static function getParser(): NodeParserInterface
    {
        return new OrgParser();
    }

    public function isAllowedMultipleTimes(): bool
    {
        return false;
    }
}
