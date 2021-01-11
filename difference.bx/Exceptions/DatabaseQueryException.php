<?php

use JetBrains\PhpStorm\Pure;

class DatabaseQueryException extends Exception
{
    
    #[Pure]
    public function __construct(string $message)
    {
        parent::__construct("[DatabaseQuery] {$message}");
    }

}