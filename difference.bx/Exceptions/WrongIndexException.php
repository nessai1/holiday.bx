<?php


class WrongIndexException extends Exception
{
    private int $index;

    public function __construct(int $index, int $code = 1)
    {
        $this->index = $index;
        parent::__construct('Function get wrong index('.$index.')', $code);
    }

    public function getIndex() : int
    {
        return $this->index;
    }
}