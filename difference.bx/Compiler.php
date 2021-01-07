<?php

include_once (__DIR__ . 'Document/ModifyTextDocument.php');

class Compiler
{
    public static function compare(ModifyTextDocument &$before, ModifyTextDocument &$after) : void
    {
      self::clearState($before);
      self::clearState($after);

      // TODO: some manipulation with files
    }

    /**
     * Function that set all states of file to 'stable'
     * @param ModifyTextDocument $document link to modify doc
     * @throws WrongIndexException
     */
    public static function clearState(ModifyTextDocument &$document) : void
    {
        for ($i = 0; $i < $document->getSize(); $i++)
        {
            $document->setState($i, 'stable');
        }
    }

    /**
     * Function that return count of match of 2 strings
     * @param string $str1 first string
     * @param string $str2 second string
     * @return int count of matches $str1 and $str2
     */
    public static function findMatches(string $str1, string $str2) : int
    {
        $matches = 0;
        $minSizeOfStrings = min(strlen($str1), strlen($str2));
        for ($i = 0; $i < $minSizeOfStrings; $i++)
        {
            if ($str1[$i] == $str2[$i])
            {
                $matches++;
            }
        }
        return $matches;
    }
}