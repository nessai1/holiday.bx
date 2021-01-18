<?php




class JSONReadException extends Exception
{

    public function __construct(string $message)
    {
        parent::__construct("[JSON Read Exception] {$message}");
    }

}