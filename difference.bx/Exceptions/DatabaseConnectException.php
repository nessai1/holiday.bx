<?php

use JetBrains\PhpStorm\Pure;

class DatabaseConnectException extends Exception
{
    private string $path;

    #[Pure]
    public function __construct(string $message)
    {
        parent::__construct($message);
    }

}