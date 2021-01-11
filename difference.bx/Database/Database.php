<?php

require (__DIR__ . '/../Exceptions/DatabaseConnectException.php');
require (__DIR__ . '/../Exceptions/DatabaseQueryException.php');

class Database
{
    private static self $instance;
    protected mysqli $connection;

    protected function __clone(){}
    protected function __wakeup(){}
    protected function __construct()
    {
        $this->connection = mysqli_init();
        $configureArray = $this->getDatabaseInfo();
        $connectionResult = $this->connection->real_connect($configureArray['host'], $configureArray['username'],
        $configureArray['password'], $configureArray['databaseName']);

        try
        {
            if (!$connectionResult)
            {
                throw new DatabaseConnectException("Database connection error: {$this->connection->connect_error}");
            }

            $setCharsetResult = $this->connection->set_charset('utf8');
            if (!$setCharsetResult)
            {
                throw new DatabaseConnectException("Database set charset error: {$this->connection->error}");
            }
        }
        catch (DatabaseConnectException $e)
        {
            // TODO: logger and exit
        }

    }

    public static function getInstance() : self
    {
        if (!isset(self::$instance))
        {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function makeQuery(string $query) : void
    {
        try
        {
            $queryResult = $this->connection->query($query);
            if (!$queryResult)
            {
                throw new DatabaseConnectException("An error occurred during the request");
            }
        }
        catch (DatabaseConnectException $e)
        {
            // TODO: some log and exit
        }

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
        return $configureArray;
    }


}