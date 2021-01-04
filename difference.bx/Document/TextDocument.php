<?php

include_once("Document.php");

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

    public function __construct(array $linesArray)
    {
        $this->fileContent = $linesArray;
        $this->fileSize = count($linesArray);
    }

    protected int $fileSize;
    protected array $fileContent;
}

