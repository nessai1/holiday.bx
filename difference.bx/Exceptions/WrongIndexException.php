<?php


class WrongIndexException extends Exception
{
    private int $index;

    public function __construct($index)
    {
        $this->index = $index;
        parent::__construct('Function get wrong index('.$index.')');
    }

    public function getIndex() : int
    {
        return $this->index;
    }
}