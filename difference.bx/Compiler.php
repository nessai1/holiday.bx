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

    public static function clearState(ModifyTextDocument &$document) : void
    {
        for ($i = 0; $i < $document->getSize(); $i++)
        {
            $document->setState($i, 'stable');
        }
    }
}