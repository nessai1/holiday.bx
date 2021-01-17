<?php

require_once (__DIR__ . '/Database.php');
require_once (__DIR__ . '/../Document/ModifyTextDocument.php');
require_once (__DIR__ . '/../Exceptions/DataReceiverException.php');


class DataReceiver
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

    protected function getColumnFromTable(array $sourceTable, int $colPosition) : array
    {
        $resultTable = array();
        foreach ($sourceTable as $value)
        {
            $resultTable[] = $value[$colPosition];
        }
        return $resultTable;
    }

    protected function getFileContentByID(int $id) : array
    {
        $queryResult = $this->database->makeQuery("SELECT CONTENT FROM file_content WHERE FILE_ID = {$id} ORDER BY LINE");
        return $this->getColumnFromTable($queryResult->fetch_all(), 0);
    }

    protected function getFileStatesByID(int $id) : array
    {
        $queryResult = $this->database->makeQuery("SELECT LINE_STATE FROM file_state WHERE FILE_ID = {$id} ORDER BY LINE");
        return $this->getColumnFromTable($queryResult->fetch_all(), 0);
    }

    protected function getFileNameByID(int $id) : string
    {
        $queryResult = $this->database->makeQuery("SELECT FILE_NAME FROM files WHERE ID = {$id}");
        $assocArrayFromQuery = $queryResult->fetch_assoc();
        return $assocArrayFromQuery['FILE_NAME'];
    }


    public function getCompareSession(int $sessionID, &$firstTextDoc, &$secondTextDoc)
    {
        try {
            $filesIDs = $this->database->makeQuery("SELECT FIRST_FILE, SECOND_FILE FROM compare WHERE ID = {$sessionID}")->fetch_assoc();
            if ($filesIDs === null) {
                throw new DataReceiverException("comparison with input ID not found");
            }
            $firstTextDoc = new ModifyTextDocument(new TextDocument($this->getFileContentByID($filesIDs['FIRST_FILE'])));
            $secondTextDoc = new ModifyTextDocument(new TextDocument($this->getFileContentByID($filesIDs['SECOND_FILE'])));

            $firstFileName = $this->getFileNameByID($filesIDs['FIRST_FILE']);
            $secondFileName = $this->getFileNameByID($filesIDs['SECOND_FILE']);

            $firstTextDoc->setName($firstFileName);
            $secondTextDoc->setName($secondFileName);

            $firstFileStates = $this->getFileStatesByID($filesIDs['FIRST_FILE']);
            $secondFileStates = $this->getFileStatesByID($filesIDs['SECOND_FILE']);

            for ($i = 0; $i < $firstTextDoc->getSize(); $i++)
            {
                $firstTextDoc->setState($i, $firstFileStates[$i]);
            }

            for ($i = 0; $i < $secondTextDoc->getSize(); $i++)
            {
                $secondTextDoc->setState($i, $secondFileStates[$i]);
            }
        }
        catch (DataReceiverException $e)
        {
            $this->logger->log($e->getMessage());
            throw new DataReceiverException("An occurred error while finding compared files");
        }
        catch (DatabaseQueryException $e)
        {
            $message = "An occurred database query error while finding compare files: {$e->getMessage()}";
            $this->logger->log($message);
            throw new DataReceiverException($message);
        }
        return $filesIDs;
    }

    public function getAllCompareSessionsInfo() : array
    {
        try
        {
            $query =
                "SELECT a.ID as ID, a.COMPARE_DATE as COMPARE_DATE, b.FILE_NAME as FIRST_FILE, c.FILE_NAME as SECOND_FILE FROM compare a
                LEFT JOIN files b  on b.ID = a.FIRST_FILE
                LEFT JOIN files c on c.ID = a.SECOND_FILE";
            $queryResult = $this->database->makeQuery($query);
            $queryResultArray = $queryResult->fetch_all(MYSQLI_ASSOC);
        }
        catch (DatabaseQueryException $e)
        {
            $message = "An occurred database query error while getting compare sessions info: {$e->getMessage()}";
            $this->logger->log($message);
            throw new DataReceiverException($message);
        }
        return $queryResultArray;
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