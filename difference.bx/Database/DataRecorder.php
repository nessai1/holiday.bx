<?php

require_once (__DIR__ . '/Database.php');
require_once (__DIR__ . '/../Logger.php');
require_once (__DIR__ . '/../Document/ModifyTextDocument.php');
require_once (__DIR__ . '/../Exceptions/DataRecorderException.php');

class DataRecorder {

    private static self $instance;
    protected Database $database;
    protected Logger $logger;

    protected function __clone(){}
    protected function __construct(Database $db)
    {
        $this->database = $db;
        $this->logger = Logger::getInstance();
    }

    /* Transaction queries */

    protected function rollback() : void
    {
        $this->database->makeQuery("ROLLBACK");
    }

    protected function startTransaction() : void
    {
        $this->database->makeQuery("START TRANSACTION");
    }

    protected function commit() : void
    {
        $this->database->makeQuery("COMMIT");
    }

    /* /Transaction queries */


    /**
     * Function that add file to database, without states
     * @param Document $file source Text file
     * @return int ID of new file
     * @throws DatabaseQueryException
     */
    public function addFile(Document $file) : int
    {
        $fileName = $file->getName();
        $fileSize = $file->getSize();
        try
        {
            $safeFileName = $this->database->prepareString($fileName);

            $query = "INSERT INTO files (FILE_NAME, FILE_LINES) VALUES ('{$safeFileName}', {$fileSize})";
            $this->database->makeQuery($query);

            $query = "SELECT MAX(ID) FROM files";
            $queryResult = $this->database->makeQuery($query);
            $firstLineResult = $queryResult->fetch_row();
            $fileID = intval($firstLineResult[0]);

            for ($i = 0; $i < $file->getSize(); $i++)
            {
                $line = $i+1;
                $content = $this->database->prepareString($file->getLine($i));

                $query = "INSERT INTO file_content (FILE_ID, LINE, CONTENT) VALUES ({$fileID}, {$line}, '{$content}')";
                $this->database->makeQuery($query);
            }
        }
        catch (DatabaseQueryException $e)
        {
            $exceptionMessage = "add file db-query error: {$e->getMessage()}";
            throw new DataRecorderException($exceptionMessage, $e->getCode());
        }
        return $fileID;
    }


    /**
     * Function that add file to database with states
     * @param ModifyTextDocument $file source file
     * @return int ID of new file
     * @throws DatabaseQueryException
     * @throws WrongIndexException
     */
    public function addModifyFile(ModifyTextDocument $file) : int
    {
        try {
            $fileID = $this->addFile($file);
            for ($i = 0; $i < $file->getSize(); $i++) {
                $line = $i + 1;
                $state = $file->getState($i);

                $query = "INSERT INTO file_state (FILE_ID, LINE, LINE_STATE) VALUES ({$fileID}, {$line}, '{$state}')";
                $this->database->makeQuery($query);
            }
        }
        catch (DatabaseQueryException $e)
        {
            $exceptionMessage = "add modify file db-query error: {$e->getMessage()}";
            throw new DataRecorderException($exceptionMessage, $e->getCode());
        }
        catch (Exception $e)
        {
            $exceptionMessage = "a logical error occurred during the operation: {$e->getMessage()}";
            throw new DataRecorderException($exceptionMessage, $e->getCode());
        }
        return $fileID;
    }

    /**
     * Function that create new record in compares table
     * @param int $firstFileID first file ID in database
     * @param int $secondFileID second file ID in database
     * @throws DatabaseQueryException
     */
    protected function createNewCompare(int $firstFileID, int $secondFileID)
    {
        try
        {
            $date = date("Y-m-d");
            $query = "INSERT INTO compare (FIRST_FILE, SECOND_FILE, COMPARE_DATE) VALUES ({$firstFileID}, {$secondFileID}, DATE('{$date}'))";
            $this->database->makeQuery($query);
        }
        catch (DatabaseQueryException $e)
        {
            $exceptionMessage = "add new compare db-query error: {$e->getMessage()}";
            throw new DataRecorderException($exceptionMessage, $e->getCode());
        }
    }


    /** not proposition function, insert modifyTextDoc content to db and recorde files-compare to compare table
     * @param ModifyTextDocument $firstFile
     * @param ModifyTextDocument $secondFile
     * @throws DatabaseQueryException
     */
    public function addCompareFiles(ModifyTextDocument $firstFile, ModifyTextDocument $secondFile) : void
    {
        $this->startTransaction();
        try
        {
            $firstFileID = $this->addModifyFile($firstFile);
            $secondFileID = $this->addModifyFile($secondFile);
            $this->createNewCompare($firstFileID, $secondFileID);
            $this->commit();
        }
        catch (Exception $e)
        {
            $this->rollback();
            $this->logger->log($e->getMessage());
            throw new DataRecorderException("an error occurred while data recording");
        }
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