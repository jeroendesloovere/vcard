<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Parser\Property;

use JeroenDesloovere\VCard\Property\Categories;
use JeroenDesloovere\VCard\Property\NodeInterface;

final class CategoriesParser implements NodeParserInterface
{
    public function parseVcfString(string $value, array $parameters = []): NodeInterface
    {
        $categories = array_map('trim', explode(',', $value));

        return new Categories($categories);
    }
}
