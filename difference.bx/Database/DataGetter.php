<?php

require_once (__DIR__ . '/../Document/ModifyTextDocument.php');


class DataGetter
{
    private static self $instance;
    protected Database $database;
    protected Logger $logger;

    protected function __clone(){}
    protected function __construct(Database $db)
    {
        $this->database = $db;
        $this->logger = Logger::getInstance();
    }

    public function getCompareSession(int $sessionID, &$firstTextDoc, &$secondTextDoc) : void
    {

    }

    public static function getInstance(Database $db) : self
    {
        if (!isset(self::$instance))
        {
            self::$instance = new self($db);
        }

        return self::$instance;
    }
}