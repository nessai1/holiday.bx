<?php

include_once (__DIR__ . '/TextDocument.php');
include_once (__DIR__ . '/../Exceptions/WrongIndexException.php');

final class ModifyTextDocument implements Document
{
    /**
     * Function that return state of line with index = $index
     * @param int $index
     * @return string = state of Document line (add/delete/edited/stable)
     * @throws WrongIndexException if index don't exist
     */
    public function getState(int $index) : string
    {
        if ($index >= $this->getSize() || $index < 0)
        {
            throw new WrongIndexException($index);
        }

        return $this->state[$index];
    }

    /**
     * Function that set state (add/delete/edited/stable) to Document line with index = $index
     * @param int $index
     * @throws WrongIndexException
     */
    public function setState(int $index, string $state) : void
    {
        if ($index >= $this->getSize() || $index < 0)
        {
            throw new WrongIndexException($index);
        }

        $this->state[$index] = $state;
    }

    public function getSize(): int
    {
        return $this->document->getSize();
    }

    public function getName(): string
    {
        return $this->document->getName();
    }

    public function getLine(int $index): ?string
    {
        return $this->document->getLine($index);
    }

    public function setName(string $name): void
    {
        $this->document->setName($name);
    }

    public function __construct(Document $document)
    {
        $this->document = $document;
        $this->state = [];
        for ($i = 0; $i < $document->getSize(); $i++)
        {
            $this->state[$i] = "stable";
        }
    }
    private Document $document;
    private array $state; // every element have value: add/delete/edited/stable
}