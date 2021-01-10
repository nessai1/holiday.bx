<?php

class Database
{
    private static self $instance;
    protected mysqli $connection;

    protected function __clone(){}
    protected function __wakeup(){}
    protected function __construct()
    {
        $this->connection = mysqli_init();

    }

    public static function getInstance() : self
    {
        if (!isset(self::$instance))
        {
            self::$instance = new self;
        }

        return self::$instance;
    }

    protected function getDatabaseInfo() : array
    {
        try
        {
            $configurePath = __DIR__ . '/databaseInfo.json';
            $configureJSON = file_get_contents($configurePath);
            if (!$configureJSON)
            {
                throw new DatabaseConnectException("Can't open configure file '{$configurePath}'");
            }

            $configureArray = json_decode($configureJSON, true);
            if (!isset($configureArray['host']) || !isset($configureArray['username'])
                || !isset($configureArray['password']) || !isset($configureArray['databaseName']))
            {
                throw new DatabaseConnectException("Wrong configure format in '{$configureJSON}'");
            }
        }
        catch (DatabaseConnectException $e)
        {
            // TODO: add header
            exit();
        }

    }

    public function sendQuerey(string $query) : void
    {
        // TODO: make query
    }
}