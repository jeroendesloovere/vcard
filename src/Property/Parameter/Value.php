<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Property\Parameter;

use JeroenDesloovere\VCard\Exception\PropertyParameterException;
use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Formatter\Property\Parameter\ValueFormatter;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Parser\Property\Parameter\ValueParser;
use JeroenDesloovere\VCard\Property\SimpleNodeInterface;

final class Value implements PropertyParameterInterface, SimpleNodeInterface
{
    protected const TEXT = 'text';
    protected const URI = 'uri';
    protected const DATE = 'date';
    protected const TIME = 'time';
    protected const DATE_TIME = 'date-time';
    protected const DATE_AND_OR_TIME = 'date-and-or-time';
    protected const TIMESTAMP = 'timestamp';
    protected const BOOLEAN = 'boolean';
    protected const INTEGER = 'integer';
    protected const FLOAT = 'float';
    protected const UTC_OFFSET = 'utc-offset';
    protected const LANGUAGE_TAG = 'language-tag';

    public const POSSIBLE_VALUES = [
        self::TEXT,
        self::URI,
        self::DATE,
        self::TIME,
        self::DATE_TIME,
        self::DATE_AND_OR_TIME,
        self::TIMESTAMP,
        self::BOOLEAN,
        self::INTEGER,
        self::FLOAT,
        self::UTC_OFFSET,
        self::LANGUAGE_TAG
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
        return new ValueFormatter($this);
    }

    public static function getNode(): string
    {
        return 'VALUE';
    }

    public static function getParser(): NodeParserInterface
    {
        return new ValueParser();
    }

    public static function text(): self
    {
        return new self(self::TEXT);
    }

    public static function uri(): self
    {
        return new self(self::URI);
    }

    public static function date(): self
    {
        return new self(self::DATE);
    }

    public static function time(): self
    {
        return new self(self::TIME);
    }

    public static function dateTime(): self
    {
        return new self(self::DATE_TIME);
    }

    public static function dateAndOrTime(): self
    {
        return new self(self::DATE_AND_OR_TIME);
    }

    public static function timestamp(): self
    {
        return new self(self::TIMESTAMP);
    }

    public static function boolean(): self
    {
        return new self(self::BOOLEAN);
    }

    public static function integer(): self
    {
        return new self(self::INTEGER);
    }

    public static function float(): self
    {
        return new self(self::FLOAT);
    }

    public static function utcOffset(): self
    {
        return new self(self::UTC_OFFSET);
    }

    public static function languageTag(): self
    {
        return new self(self::LANGUAGE_TAG);
    }
}
