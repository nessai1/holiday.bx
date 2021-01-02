<?php

include_once('TextDocument.php');

final class ModifyTextDocument extends TextDocument
{
    public function getState($index) : ?string
    {
        if ($index >= $this->fileSize || $index < 0)
        {
            return false;
        }

        return $this->state[$index];
    }

    public function __construct($linesArray, $statesArray)
    {
        $this->fileContent = $linesArray;
        $this->fileSize = count($linesArray);
        $this->state = $statesArray;
    }

    private $state;
}