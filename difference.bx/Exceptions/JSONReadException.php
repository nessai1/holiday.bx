<?php


use JetBrains\PhpStorm\Pure;

class JSONReadException extends Exception
{

    #[Pure]
    public function __construct(string $message)
    {
        parent::__construct("[JSON Read Exception] {$message}");
    }

}