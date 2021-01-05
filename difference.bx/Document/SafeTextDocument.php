<?php

include_once (__DIR__ . '/TextDocument.php');

class SafeTextDocument implements Document
{
    public function getSize(): int
    {
        return $this->document->getSize();
    }

    public function getLine(int $index): ?string
    {
        return htmlspecialchars($this->document->getLine($index));
    }

    public function getName(): string
    {
        return htmlspecialchars($this->document->getName());
    }

    public function setName(string $name): void
    {
        $this->document->setName($name);
    }

    public function __construct(TextDocument $document)
    {
        $this->document = $document;
    }

    protected TextDocument $document;
}