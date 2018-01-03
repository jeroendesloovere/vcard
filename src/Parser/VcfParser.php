<?php

namespace JeroenDesloovere\VCard\Parser;

use JeroenDesloovere\VCard\Exception\ParserException;
use JeroenDesloovere\VCard\Parser\Property\NodeParserInterface;
use JeroenDesloovere\VCard\Property\NodeInterface;
use JeroenDesloovere\VCard\VCard;

final class VcfParser implements ParserInterface
{
    /**
     * @var NodeParserInterface[] - f.e. ['ADR' => JeroenDesloovere\VCard\Parser\Property\AddressParser]
     */
    private $parsers = [];

    /**
     * @param string $content
     * @return VCard[]
     * @throws ParserException
     */
    public function getVCards(string $content): array
    {
        /**
         * @var NodeInterface $propertyClass
         */
        foreach (VCard::POSSIBLE_VALUES as $propertyClass) {
            $this->parsers[$propertyClass::getNode()] = $propertyClass::getParser();
        }

        $vCards = [];

        foreach ($this->splitIntoVCardsContent($content) as $vCardContent) {
            $vCard = $this->parseVCard($vCardContent);

            if ($vCard instanceof VCard) {
                $vCards[] = $vCard;
            }
        }

        return $vCards;
    }

    private function parseParameters(?string $parameters): array
    {
        if ($parameters === null) {
            return [];
        }

        $parsedParameters = [];
        /** @var string[] $parametersArray */
        $parametersArray = explode(';', $parameters);
        foreach ($parametersArray as $parameter) {
            /**
             * @var string $node
             * @var string $value
             */
            @list($node, $value) = explode('=', $parameter, 2);

            if (array_key_exists($node, $this->parsers)) {
                $parsedParameters[$node] = $this->parsers[$node]->parseLine($value);
            }
        }

        return $parsedParameters;
    }

    private function parseVCard(string $content): VCard
    {
        $vCard = new VCard();
        $lines = explode("\n", $content);

        foreach ($lines as $line) {
            // Strip grouping information. We don't use the group names. We
            // simply use a list for entries that have multiple values.
            // As per RFC, group names are alphanumerical, and end with a
            // period (.).
            $line = preg_replace('/^\w+\./', '', trim($line));

            /**
             * @var string $node
             * @var string $value
             */
            @list($node, $value) = explode(':', $line, 2);

            /**
             * @var string|null $parameterContent
             */
            @list($node, $parameterContent) = explode(';', $node, 2);

            if (!array_key_exists($node, $this->parsers)) {
                // @todo: add this line to "not converted" errors. Can be useful to improve the parser.

                continue;
            }

            $parameters = $this->parseParameters($parameterContent);

            try {
                $vCard->add($this->parsers[$node]->parseLine($value, $parameters));
            } catch (\Exception $e) {
                // @todo: fetch errors when setting properties that are already set.
            }
        }

        return $vCard;
    }

    /**
     * @param string $content - The full content from the .vcf file.
     * @return array - Is an array with the content for all possible vCards.
     * @throws ParserException
     */
    private function splitIntoVCardsContent(string $content): array
    {
        // Normalize new lines.
        $content = str_replace(["\r\n", "\r"], "\n", $content);

        $content = trim($content);

        if (!preg_match('/^BEGIN:VCARD[\s\S]+END:VCARD$/', $content)) {
            throw ParserException::forUnreadableVCard($content);
        }

        // Remove first BEGIN:VCARD and last END:VCARD
        $content = substr($content, 12, -10);

        // RFC2425 5.8.1. Line delimiting and folding
        // Unfolding is accomplished by regarding CRLF immediately followed by
        // a white space character (namely HTAB ASCII decimal 9 or. SPACE ASCII
        // decimal 32) as equivalent to no characters at all (i.e., the CRLF
        // and single white space character are removed).
        $content = preg_replace("/\n(?:[ \t])/", '', $content);

        // If multiple vcards split per vcard
        return preg_split('/\nEND:VCARD\s+BEGIN:VCARD\n/', $content);
    }
}
