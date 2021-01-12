<?php

require (__DIR__ . '/Document/TextDocument.php');
require (__DIR__ . '/Exceptions/PathNotFoundException.php');
require (__DIR__ . '/Exceptions/JSONReadException.php');

class FileReader
{
    /**
     * @param string $path path to the processed file
     * @return TextDocument that contain all text-lines of the file
     * @throws PathNotFoundException if $path can't be reach
     */
    public static function readTextDocument(string $path) : TextDocument
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

    /**
     * Function that read file with JSON and return it as an associative array
     * @param string $path path to the file with JSON
     * @param string|null $globalParameter the name of a global variable if you need to get only it
     * @return array associative array from JSON
     * @throws JSONReadException
     */
    public static function readJSON(string $path, string $globalParameter = null) : array
    {
            $FileContent = file_get_contents($path);
            if (!$FileContent)
            {
                throw new JSONReadException("Can't open JSON file with path '{$path}'");
            }

            $JSONArray = json_decode($FileContent, true);

            if ($JSONArray === null)
            {
                throw new JSONReadException("File cannot be converted to json ('{$path}')");
            }

            if ($globalParameter !== null)
            {
                if (!isset($JSONArray[$globalParameter]))
                {
                    throw new JSONReadException("Can't find Global set in JSON file with path '{$path}'");
                }

                return $JSONArray[$globalParameter];
            }

            return $JSONArray;
    }
}