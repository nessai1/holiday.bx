<?php

require_once("Document.php");

class TextDocument implements Document
{
    public function getSize() : int
    {
        return $this->fileSize;
    }

    public function getLine(int $index) : ?string
    {
        if ($index >= $this->fileSize || $index < 0)
        {
            return false;
        }

        return $this->fileContent[$index];
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function setName(string $name) : void
    {
        $this->name = $name;
    }

    public function __construct(array $linesArray, string $name = "unnamed")
    {
        $this->fileContent = $linesArray;
        $this->fileSize = count($linesArray);
        $this->name = $name;
    }

    protected string $name;
    protected int $fileSize;
    protected array $fileContent;
}

