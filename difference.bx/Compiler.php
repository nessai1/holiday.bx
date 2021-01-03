<?php

class Compiler
{
    private static $instances = [];

    protected function __construct(){} // singleton can't construct
    protected function __clone(){} // singleton isn't clone

    public function compare($firstPath, $secondPath)
    {

    }

    public static function getInstance() : Compiler
    {
        $compiler = static::class;
        if (!isset(self::$instances[$compiler]))
        {
            self::$instances[$compiler] = new static();
        }
        return self::$instances[$compiler];
    }
}