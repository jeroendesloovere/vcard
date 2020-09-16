<?php

declare(strict_types=1);

namespace Dilone\VCard\Property\Parameter;

use Dilone\VCard\Exception\PropertyParameterException;
use Dilone\VCard\Formatter\Property\NodeFormatterInterface;
use Dilone\VCard\Formatter\Property\Parameter\VersionFormatter;
use Dilone\VCard\Parser\Property\NodeParserInterface;
use Dilone\VCard\Parser\Property\Parameter\VersionParser;
use Dilone\VCard\Property\SimpleNodeInterface;

final class Version implements PropertyParameterInterface, SimpleNodeInterface
{
    protected const VERSION_3 = '3.0';
    protected const VERSION_4 = '4.0';

    public const POSSIBLE_VALUES = [
        self::VERSION_3,
        self::VERSION_4,
    ];

    private $value;

    /**
     * @param string $value
     * @throws PropertyParameterException
     */
    public function __construct(string $value)
    {
        if (!in_array($value, self::POSSIBLE_VALUES, true)) {
            throw PropertyParameterException::forWrongValue($value, self::POSSIBLE_VALUES);
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function getFormatter(): NodeFormatterInterface
    {
        return new VersionFormatter($this);
    }

    public static function getNode(): string
    {
        return 'VERSION';
    }

    public static function getParser(): NodeParserInterface
    {
        return new VersionParser();
    }

    public static function version3(): self
    {
        return new self(self::VERSION_3);
    }

    public function isVersion3(): bool
    {
        return $this->value === self::VERSION_3;
    }

    public static function version4(): self
    {
        return new self(self::VERSION_4);
    }

    public function isVersion4(): bool
    {
        return $this->value === self::VERSION_4;
    }
}
