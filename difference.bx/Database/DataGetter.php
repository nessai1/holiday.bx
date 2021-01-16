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

    protected function getFileContentByID($id) : array
    {
        $queryResult = $this->database->makeQuery("SELECT CONTENT FROM file_content WHERE FILE_ID = {$id} ORDER BY LINE");
        return $queryResult->fetch_all();
    }

    protected function getFileStatesByID($id) : array
    {
        $queryResult = $this->database->makeQuery("SELECT LINE_STATE FROM file_state WHERE FILE_ID = {$id} ORDER BY LINE");
        return $queryResult->fetch_all();
    }

    protected function getFileNameByID($id) : array
    {
        $queryResult = $this->database->makeQuery("SELECT FILE_NAME FROM files WHERE ID = {$id}");
        return $queryResult->fetch_assoc();
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

            $firstFileInfo = $this->getFileNameByID($filesIDs['FIRST_FILE']);
            $secondFileInfo = $this->getFileNameByID($filesIDs['SECOND_FILE']);

            $firstTextDoc->setName($firstFileInfo['FILE_NAME']);
            $secondTextDoc->setName($secondFileInfo['FILE_NAME']);


        }
        catch (DataGetterException $e)
        {
            $this->logger->log($e->getMessage());
            throw new DataGetterException("An occurred error while finding compared files");
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