<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    // PHP 8.1 as minimum version (library minimum)
    $rectorConfig->phpVersion(80100);

    // Import sets for modern PHP coding
    $rectorConfig->sets([
        SetList::DEAD_CODE,
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
        SetList::TYPE_DECLARATION,
        SetList::STRICT_BOOLEANS,
        SetList::EARLY_RETURN,
        SetList::NAMING,
        PHPUnitSetList::PHPUNIT_100,
    ]);

    // Skip certain rules we don't want applied
    $rectorConfig->skip([
    ]);
};
