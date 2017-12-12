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

    /**
     * Formatter constructor.
     *
     * @param FormatterInterface $formatter
     * @param string             $fileName
     */
    public function __construct(FormatterInterface $formatter, string $fileName)
    {
        $this->formatter = $formatter;
        $this->fileName = $fileName;
    }

    /**
     * @param VCard $vCard
     *
     * @return Formatter
     */
    public function addVCard(VCard $vCard): self
    {
        $this->vCards[] = $vCard;

        return $this;
    }

    /**
     *
     */
    public function download(): void
    {
        foreach ($this->getHeaders() as $header) {
            header($header);
        }

        echo $this->getContent();
    }

    /**
     * @return string
     */
    public function getCharset(): string
    {
        return $this->charset;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->formatter->getContent($this->vCards);
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @return string
     */
    public function getFullFileName(): string
    {
        return $this->getFileName().'.'.$this->formatter->getFileExtension();
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return [
            'Content-type' => $this->formatter->getContentType().'; charset='.$this->getCharset(),
            'Content-Disposition' => 'attachment; filename='.$this->getFullFileName(),
            'Content-Length' => mb_strlen($this->formatter->getContent($this->vCards), $this->getCharset()),
            'Connection' => 'close',
        ];
    }

    /**
     * @return array
     */
    public function getVCards(): array
    {
        return $this->vCards;
    }

    /**
     * @param string $toPath
     */
    public function save(string $toPath): void
    {
        $filePath = rtrim($toPath, '/').'/'.$this->getFullFileName();

        file_put_contents(
            $filePath,
            $this->getContent()
        );
    }

    /**
     * @param string $charset
     */
    public function setCharset(string $charset): void
    {
        $this->charset = $charset;
    }
}
