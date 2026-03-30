<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Formatter\Property\CategoriesFormatter;
use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Parser\Property\CategoriesParser;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;

final class Categories implements PropertyInterface, NodeInterface
{
    /** @var string[] */
    private $categories;

    /**
     * @param string[] $categories
     */
    public function __construct(array $categories)
    {
        $this->categories = $categories;
    }

    public function __toString(): string
    {
        return implode(',', $this->categories);
    }

    public function getFormatter(): NodeFormatterInterface
    {
        return new CategoriesFormatter($this);
    }

    /**
     * @return string[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    public static function getNode(): string
    {
        return 'CATEGORIES';
    }

    public static function getParser(): NodeParserInterface
    {
        return new CategoriesParser();
    }

    public function isAllowedMultipleTimes(): bool
    {
        return false;
    }
}
