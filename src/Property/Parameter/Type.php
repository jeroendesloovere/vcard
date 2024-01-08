<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Property\Parameter;

use JeroenDesloovere\VCard\Exception\PropertyParameterException;
use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Formatter\Property\Parameter\TypeFormatter;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Parser\Property\Parameter\TypeParser;
use JeroenDesloovere\VCard\Property\SimpleNodeInterface;

final class Type implements PropertyParameterInterface, SimpleNodeInterface
{
    protected const HOME = 'home';
    protected const WORK = 'work';

    public const POSSIBLE_VALUES = [
        self::HOME,
        self::WORK,
    ];

    private $value;

    /**
     * @param string $value
     * @throws PropertyParameterException
     */
    public function __construct(string $value)
    {
        if (!in_array($value, self::POSSIBLE_VALUES, true)) {
            $value = self::HOME;
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function getFormatter(): NodeFormatterInterface
    {
        return new TypeFormatter($this);
    }

    public static function getNode(): string
    {
        return 'TYPE';
    }

    public static function getParser(): NodeParserInterface
    {
        return new TypeParser();
    }

    public static function home(): self
    {
        return new self(self::HOME);
    }

    public function isHome(): bool
    {
        return $this->value === self::HOME;
    }

    public static function work(): self
    {
        return new self(self::WORK);
    }

    public function isWork(): bool
    {
        return $this->value === self::WORK;
    }
}
