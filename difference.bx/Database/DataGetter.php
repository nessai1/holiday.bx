<?php

require_once (__DIR__ . '/Database.php');
require_once (__DIR__ . '/../Document/ModifyTextDocument.php');
require_once (__DIR__ . '/../Exceptions/DataGetterException.php');


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
                throw new DataGetterException("comparison with input ID not found");
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
        catch (DataGetterException $e)
        {
            $this->logger->log($e->getMessage());
            throw new DataGetterException("An occurred error while finding compared files");
        }
        catch (DatabaseQueryException $e)
        {
            $message = "An occurred database query error while finding compare files: {$e->getMessage()}";
            $this->logger->log($message);
            throw new DataGetterException($message);
        }
        return $filesIDs;
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