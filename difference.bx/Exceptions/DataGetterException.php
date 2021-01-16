<?php

class DataGetterException extends Exception
{
    public function __construct($message, $code = 2)
    {
        parent::__construct("[DataGetter] {$message}", $code);
    }
}