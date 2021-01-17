<?php

class DataReceiverException extends Exception
{
    public function __construct($message, $code = 2)
    {
        parent::__construct("[DataReceiver] {$message}", $code);
    }
}