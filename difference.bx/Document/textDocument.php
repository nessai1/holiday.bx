<?php

require("Document.php");

final class textDocument implements Document
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

    public function __construct($linesArray)
    {
        $this->fileContent = $linesArray;
        $this->fileSize = count($linesArray);
    }

    private $fileSize;
    private $fileContent;
}

