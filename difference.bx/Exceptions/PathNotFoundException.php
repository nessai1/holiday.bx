<?php



class PathNotFoundException extends Exception
{

    private string $path;

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