<?php

use JetBrains\PhpStorm\Pure;

class DatabaseConnectException extends Exception
{

    #[Pure]
    public function __construct(string $message)
    {
        parent::__construct("[DatabaseConnect] {$message}");
    }

}