<?php

include_once (__DIR__ . '/Document/TextDocument.php');
include_once (__DIR__ . '/Exceptions/PathNotFoundException.php');

class FileReader
{
    /**
     * @param string $path path to the processed file
     * @return TextDocument that contain all text-lines of the file
     * @throws PathNotFoundException if $path can't be reach
     */
    public static function readPath(string $path) : TextDocument
    {
        try
        {
            $resultFile = file($path, FILE_IGNORE_NEW_LINES);
        }
        catch (Exception $e)
        {
            throw new PathNotFoundException($path);
        }
        return new TextDocument($resultFile);
    }
}