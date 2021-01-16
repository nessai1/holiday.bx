<?php

class DataRecorderException extends Exception
{
    public function __construct($message, $code = 2)
    {
        parent::__construct("[DataRecorder] {$message}", $code);
    }
}