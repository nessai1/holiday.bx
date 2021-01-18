<?php



class PathNotFoundException extends Exception
{

    private string $path;

    public function __construct(string $path, int $code = 3)
    {
        parent::__construct('Path '.$path.' is not found', $code);
        $this->path = $path;
    }

    public function getPath() : string
    {
        return $this->path;
    }
}