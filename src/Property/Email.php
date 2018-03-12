<?php

namespace JeroenDesloovere\VCard\Property;

use JeroenDesloovere\VCard\Exception\PropertyException;
use JeroenDesloovere\VCard\Formatter\Property\EmailFormatter;
use JeroenDesloovere\VCard\Formatter\Property\NodeFormatterInterface;
use JeroenDesloovere\VCard\Parser\Property\EmailParser;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Property\Parameter\Type;

final class Email implements PropertyInterface, NodeInterface
{
    /** @var null|string */
    private $email;

    /** @var Type */
    private $type;

    public function __construct(
        ?string $email = null,
        Type $type = null
    ) {
        if ($email === null && $type === null) {
            throw PropertyException::forEmptyProperty();
        }

        $this->email = $email;
        $this->type = $type ?? Type::home();
    }

    public function getFormatter(): NodeFormatterInterface
    {
        return new EmailFormatter($this);
    }

    public static function getNode(): string
    {
        return 'EMAIL';
    }

    public static function getParser(): NodeParserInterface
    {
        return new EmailParser();
    }

    public function isAllowedMultipleTimes(): bool
    {
        return true;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function setType(Type $type)
    {
        $this->type = $type;
    }
}
