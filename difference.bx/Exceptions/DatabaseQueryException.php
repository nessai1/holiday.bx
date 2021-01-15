<?php

use JetBrains\PhpStorm\Pure;

class DatabaseQueryException extends Exception
{
    
    #[Pure]
    public function __construct(string $message, int $code = 2)
    {
        parent::__construct("[DatabaseQuery] {$message}", $code);
    }

}