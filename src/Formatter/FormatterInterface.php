<?php

namespace JeroenDesloovere\VCard\Formatter;

/**
 * Interface FormatterInterface
 *
 * @package JeroenDesloovere\VCard\Formatter
 */
interface FormatterInterface
{
    /**
     * @param array $vCards
     *
     * @return string
     */
    public function getContent(array $vCards): string;

    /**
     * @return string
     */
    public function getContentType(): string;

    /**
     * @return string
     */
    public function getFileExtension(): string;
}
