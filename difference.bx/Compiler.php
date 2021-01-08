<?php

include_once (__DIR__ . '/Document/ModifyTextDocument.php');

class Compiler
{
    /**
     * Function that get links to before and after edit text files and set it right edit states for this two objects
     * @param ModifyTextDocument $before link to the first file, before edit
     * @param ModifyTextDocument $after link to the second file, after edit
     * @throws WrongIndexException
     */
    public static function compare(ModifyTextDocument &$before, ModifyTextDocument &$after) : void
    {
      self::clearState($before);
      self::clearState($after);

      $i = 0;
      $j = 0;
      for (; $i < $before->getSize(); $i++)
      {
          $matchExist = false;
          for (; $j < $after->getSize(); $j++)
          {
            if ($before->getLine($i) == $after->getLine($j))
            {
                $matchExist = true; // leave 'stable' state-value
                $j++;
                break;
            }
            else if (Compiler::findMatches($before->getLine($i), $after->getLine($j)) > 0)
            {
                $matchExist = true;
                $before->setState($i, 'edited');
                $after->setState($j, 'edited');
                $j++;
                break;
            }
            $after->setState($j, 'add');
          }
          if (!$matchExist)
          {
              $before->setState($i, 'delete');
          }
      }
      for (; $j < $after->getSize(); $j++)
      {
          $after->setState($j, 'add');
      }
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