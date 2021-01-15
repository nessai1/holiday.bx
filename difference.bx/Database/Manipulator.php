<?php

require_once (__DIR__ . '/Database.php');
require_once (__DIR__ . '/../Logger.php');
require_once (__DIR__ . '/../Document/ModifyTextDocument.php');

class Manipulator {

    private static self $instance;
    protected Database $database;

    protected function __clone(){}
    protected function __construct(Database $db)
    {
        $this->database = $db;
    }

    /* Transaction queries */

    public function rollback() : void
    {
        $this->database->makeQuery("ROLLBACK");
    }

    public function startTransaction() : void
    {
        $this->database->makeQuery("START TRANSACTION");
    }

    public function commit() : void
    {
        $this->database->makeQuery("COMMIT");
    }

    /* /Transaction queries */

    public function addFile(Document $file) : int
    {
        $fileName = $file->getName();
        $fileSize = $file->getSize();
        try
        {
            $this->database->makeQuery("INSERT INTO files (FILE_NAME, FILE_LINES) VALUES ('{$fileName}', {$fileSize})");
            $result = $this->database->makeQuery("SELECT MAX(ID) FROM files");
            $firstLineResult = $result->fetch_row();
            $fileID = intval($firstLineResult[0]);

            for ($i = 0; $i < $file->getSize(); $i++)
            {
                $line = $i+1;
                $content = $this->database->prepareString($file->getLine($i));
                $this->database->makeQuery("INSERT INTO file_content (FILE_ID, LINE, CONTENT) VALUES ({$fileID}, {$line}, '{$content}')");
            }
        }
        catch (DatabaseQueryException $e)
        {
            throw new DatabaseQueryException($e->getMessage(), $e->getCode());
        }
        return $fileID;
    }

    public function addCompareFiles(ModifyTextDocument $firstFile, ModifyTextDocument $secondFile) : void
    {
        $this->startTransaction();
        try
        {

            $this->commit();
        }
        catch (Exception $e)
        {
            $this->rollback();
            $logger = Logger::getInstance();
            $logger->log($e->getMessage());
            throw new DatabaseQueryException("[Database query] Database query error");
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