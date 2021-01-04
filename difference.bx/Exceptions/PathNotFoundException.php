<?php

use JetBrains\PhpStorm\Pure;

class PathNotFoundException extends Exception
{

    private string $path;

    #[Pure]
    public function __construct(string $path)
    {
        parent::__construct('Path '.$path.' is not found');
        $this->path = $path;
    }

    public function getPath() : string
    {
        return $this->path;
    }
}