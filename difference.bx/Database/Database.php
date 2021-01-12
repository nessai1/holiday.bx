<?php

require_once (__DIR__ . '/../FileReader.php');
require_once (__DIR__ . '/../Logger.php');
require_once (__DIR__ . '/../Exceptions/DatabaseConnectException.php');
require_once (__DIR__ . '/../Exceptions/DatabaseQueryException.php');

class Database
{
    private static self $instance;
    protected mysqli $connection;

    protected function __clone(){}
    protected function __wakeup(){}
    protected function __construct()
    {
        try
        {
            $this->connection = mysqli_init();
            $configureArray = $this->getDatabaseInfo();
            $connectionResult = $this->connection->real_connect($configureArray['host'], $configureArray['username'],
                $configureArray['password'], $configureArray['databaseName']);


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
        catch (DatabaseConnectException | JSONReadException $e)
        {
            $logger = Logger::getInstance();
            $logger->log($e->getMessage());
            exit($e->getMessage());
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

    public function makeQuery(string $query): mysqli_result|bool
    {
        $queryResult = $this->connection->query($query);
        if (!$queryResult)
        {
            throw new DatabaseQueryException("An error occurred during the request");
        }
        return $queryResult;
    }

    protected function getDatabaseInfo() : array
    {
        $configurePath = __DIR__ . '/../config.json';
        $configureJSON = FileReader::readJSON($configurePath, 'Database');

        if (!isset($configureJSON['host']) || !isset($configureJSON['username'])
            || !isset($configureJSON['password']) || !isset($configureJSON['databaseName']))
        {
            throw new JSONReadException("Wrong configure format in '{$configurePath}' JSON file");
        }
        return $configureJSON;
    }


}