<?php



class DatabaseConnectException extends Exception
{

    public function __construct(string $message, int $code = 2)
    {
        parent::__construct("[DatabaseConnect] {$message}", $code);
    }

}