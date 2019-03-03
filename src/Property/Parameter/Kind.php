<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Property\Parameter;

use JeroenDesloovere\VCard\Exception\PropertyParameterException;
use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Formatter\Property\Parameter\KindFormatter;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Parser\Property\Parameter\KindParser;
use JeroenDesloovere\VCard\Property\SimpleNodeInterface;

/**
 * vCard defines "Kinds" to represent the types of objects to be represented by vCard.
 */
final class Kind implements PropertyParameterInterface, SimpleNodeInterface
{
    // group - To represent groups of vCard objects
    protected const GROUP = 'group';

    // individual - To represent people
    protected const INDIVIDUAL = 'individual';

    // location - To represent location objects
    protected const LOCATION = 'location';

    // org - To represent organisations
    protected const ORGANIZATION = 'org';

    public const POSSIBLE_VALUES = [
        self::GROUP,
        self::INDIVIDUAL,
        self::LOCATION,
        self::ORGANIZATION,
    ];

    private $value;

    /**
     * @param string $value
     * @throws PropertyParameterException
     */
    public function __construct(string $value)
    {
        $value = strtolower($value);
        if (!in_array($value, self::POSSIBLE_VALUES, true)) {
            // If value is absent or not understood 'individual' must be used,
            // as stated in RFC 6350
            $this->value = self::INDIVIDUAL;
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function getFormatter(): NodeFormatterInterface
    {
        return new KindFormatter($this);
    }

    public static function getNode(): string
    {
        return 'KIND';
    }

    public static function getParser(): NodeParserInterface
    {
        return new KindParser();
    }

    public static function group(): self
    {
        return new self(self::GROUP);
    }

    public function isGroup(): bool
    {
        return $this->value === self::GROUP;
    }

    public static function individual(): self
    {
        return new self(self::INDIVIDUAL);
    }

    public function isIndividual(): bool
    {
        return $this->value === self::INDIVIDUAL;
    }

    public static function location(): self
    {
        return new self(self::LOCATION);
    }

    public function isLocation(): bool
    {
        return $this->value === self::LOCATION;
    }

    public static function organization(): self
    {
        return new self(self::ORGANIZATION);
    }

    public function isOrganization(): bool
    {
        return $this->value === self::ORGANIZATION;
    }
}
