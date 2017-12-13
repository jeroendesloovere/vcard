<?php

namespace JeroenDesloovere\VCard\Formatter;

use JeroenDesloovere\VCard\Exception\VCardException;
use JeroenDesloovere\VCard\VCard;

class Formatter
{
    /**
     * @var string
     */
    private $charset = 'utf-8';

    /**
     * @var string
     */
    private $fileName;

    /**
     * @var FormatterInterface
     */
    private $formatter;

    /**
     * @var VCard[]
     */
    private $vCards;

    public function __construct(FormatterInterface $formatter, string $fileName)
    {
        $this->formatter = $formatter;
        $this->fileName = $fileName;
    }

    public function addVCard(VCard $vCard): self
    {
        $this->vCards[] = $vCard;

        return $this;
    }

    public function download(): void
    {
        foreach ($this->getHeaders() as $header) {
            header($header);
        }

        echo $this->getContent();
    }

    public function getCharset(): string
    {
        return $this->charset;
    }

    public function getContent(): string
    {
        return $this->formatter->getContent($this->vCards);
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getFullFileName(): string
    {
        return $this->getFileName() . '.' . $this->formatter->getFileExtension();
    }

    public function getHeaders(): array
    {
        return [
            'Content-type' => $this->formatter->getContentType() . '; charset=' . $this->getCharset(),
            'Content-Disposition' => 'attachment; filename=' . $this->getFullFileName(),
            'Content-Length' => mb_strlen($this->getContent(), $this->getCharset()),
            'Connection' => 'close',
        ];
    }

    public function getVCards(): array
    {
        return $this->vCards;
    }

    public function save(string $toPath): bool
    {
        try {
            $savedBytes = file_put_contents(
                rtrim($toPath, '/') . '/' . $this->getFullFileName(),
                $this->getContent()
            );

            return (int) $savedBytes > 0;
        } catch (\Exception $e) {
            throw new VCardException($e->getMessage());
        }
    }

    public function setCharset(string $charset): void
    {
        $this->charset = $charset;
    }
}
