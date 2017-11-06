<?php

namespace JeroenDesloovere\VCard\Formatter;

use JeroenDesloovere\VCard\VCard;

class Formatter
{
    /** @var string */
    private $charset = 'utf-8';

    /** @var string */
    private $fileName;

    /** @var FormatterInterface */
    private $formatter;

    /** @var array */
    private $vCards;

    public function __construct(FormatterInterface $formatter, string $fileName)
    {
        $this->formatter = $formatter;
        $this->fileName = $fileName;
    }

    public function addVCard(VCard $vCard)
    {
        $this->vCards[] = $vCard;
    }

    public function download()
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
        return $this->formatter->getContent();
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getHeaders(): array
    {
        return [
            'Content-type' => $this->formatter->getContentType() . '; charset=' . $this->getCharset(),
            'Content-Disposition' => 'attachment; filename=' . $this->getFileName() . '.' . $this->formatter->getFileExtension(),
            'Content-Length' => mb_strlen($this->formatter->getContent(), $this->getCharset()),
            'Connection' => 'close',
        ];
    }

    public function getVCards(): array
    {
        return $this->vCards;
    }

    public function save(string $toPath)
    {
        $filePath = rtrim($toPath, '/') . '/' . $this->getFileName() . '.' . $this->formatter->getFileExtension();

        file_put_contents(
            $filePath,
            $this->getContent()
        );
    }

    public function setCharset(string $charset)
    {
        $this->charset = $charset;
    }
}
