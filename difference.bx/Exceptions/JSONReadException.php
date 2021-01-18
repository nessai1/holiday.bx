<?php




class JSONReadException extends Exception
{

    public function __construct(string $message, int $code = 3)
    {
        parent::__construct("[JSON Read Exception] {$message}", $code);
    }

}