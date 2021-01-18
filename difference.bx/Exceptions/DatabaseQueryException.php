<?php



class DatabaseQueryException extends Exception
{

    public function __construct(string $message, int $code = 2)
    {
        parent::__construct("[DatabaseQuery] {$message}", $code);
    }

}