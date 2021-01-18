<?php



class DatabaseConnectException extends Exception
{

    public function __construct(string $message)
    {
        parent::__construct("[DatabaseConnect] {$message}");
    }

}