<?php

class Logger
{
    private static self $instance;
    protected $descriptor;

    protected function __clone(){}

    protected function __construct()
    {
        $this->descriptor = fopen("log.txt", "a") or die("Fatal error: can't open log file");
    }

    public function __destruct()
    {
        fclose($this->descriptor);
    }

    public static function getInstance() : self
    {
        if(!isset(self::$instance))
        {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function log(string $message) : void
    {
        $date = date("[d.m.y][h:i:s]");
        $logMessage = $date.' '.$message.PHP_EOL;
        fwrite($this->descriptor, $logMessage);
    }
}