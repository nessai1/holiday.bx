<?php

use PHPUnit\Framework\TestCase;

include_once (__DIR__ . "/../../difference.bx/Compiler.php");
include_once (__DIR__ . "/../../difference.bx/Document/SafeTextDocument.php");
include_once (__DIR__ . "/../../difference.bx/FileReader.php");

class CompilerTest extends TestCase
{
    public function testMatches() : void
    {
        $line1 = "hello";
        $line2 = "hallo";
        $line3 = "asty";
        $line4 = "home";
        self::assertEquals(4, Compiler::findMatches($line1, $line2));
        self::assertEquals(0, Compiler::findMatches($line1, $line3));
        self::assertEquals(1, Compiler::findMatches($line1, $line4));
    }

    public function testEqualFiles() : void
    {
        $fileLines = ['Hello world', 'Hello PHP', 'How are you?'];
        $firstFile = new ModifyTextDocument(new SafeTextDocument(new TextDocument($fileLines)));
        $secondFile = new ModifyTextDocument(new SafeTextDocument(new TextDocument($fileLines)));
        Compiler::compare($firstFile, $secondFile);

        for ($i = 0; $i < count($fileLines); $i++)
        {
            self::assertEquals('stable', $firstFile->getState($i));
            self::assertEquals('stable', $secondFile->getState($i));
        }
    }

    public function testUnequalFilesCase1() : void
    {
        $beforeLines = ['Hello world', 'Hello PHP'];
        $afterLines = ['Hello world', 'test message', 'Hello PHP'];
        $firstFile = new ModifyTextDocument(new SafeTextDocument(new TextDocument($beforeLines)));
        $secondFile = new ModifyTextDocument(new SafeTextDocument(new TextDocument($afterLines)));
        Compiler::compare($firstFile,$secondFile);


        self::assertEquals('stable', $firstFile->getState(0));
        self::assertEquals('edited', $firstFile->getState(1));

        self::assertEquals('stable', $secondFile->getState(0));
        self::assertEquals('edited', $secondFile->getState(1));
        self::assertEquals('add', $secondFile->getState(2));
    }

    public function testUnequalFilesCase2() : void
    {
        $beforeLines = [];
        $afterLines = ['Hello world', 'Hello PHP'];
        $firstFile = new ModifyTextDocument(new SafeTextDocument(new TextDocument($beforeLines)));
        $secondFile = new ModifyTextDocument(new SafeTextDocument(new TextDocument($afterLines)));
        Compiler::compare($firstFile, $secondFile);
        for ($i = 0; $i < $secondFile->getSize(); $i++)
        {
            self::assertEquals('add',$secondFile->getState($i));
        }
    }

    public function testUnequalFilesCase3() : void
    {
        $beforeLines = ['Love PHP', 'Love World'];
        $afterLines = ['Hello PHP', 'Hello World', 'Love PHP', 'Love World'];
        $firstFile = new ModifyTextDocument(new SafeTextDocument(new TextDocument($beforeLines)));
        $secondFile = new ModifyTextDocument(new SafeTextDocument(new TextDocument($afterLines)));
        Compiler::compare($firstFile, $secondFile);
        for ($i = 0; $i < $firstFile->getSize(); $i++)
        {
            self::assertEquals('stable', $firstFile->getState($i));
        }
        self::assertEquals('add', $secondFile->getState(0));
        self::assertEquals('add', $secondFile->getState(1));
        self::assertEquals('stable', $secondFile->getState(2));
        self::assertEquals('stable', $secondFile->getState(3));
    }

    public function testUnequalFilesCase4() : void
    {
        $firstFile = new ModifyTextDocument(new SafeTextDocument(
            FileReader::readTextDocument(__DIR__ . '/tmpFiles/unequalFirstCase.txt')));
        $secondFile = new ModifyTextDocument(new SafeTextDocument(
            FileReader::readTextDocument(__DIR__ . '/tmpFiles/unequalSecondCase.txt')));

        Compiler::compare($firstFile, $secondFile);

        // first file check
        self::assertEquals('stable', $firstFile->getState(0));
        self::assertEquals('edited', $firstFile->getState(1));
        self::assertEquals('stable', $firstFile->getState(2));
        self::assertEquals('edited', $firstFile->getState(3));
        self::assertEquals('edited', $firstFile->getState(4));
        self::assertEquals('stable', $firstFile->getState(5));
        self::assertEquals('delete', $firstFile->getState(6));

        // second file check
        self::assertEquals('stable', $secondFile->getState(0));
        self::assertEquals('edited', $secondFile->getState(1));
        self::assertEquals('add', $secondFile->getState(2));
        self::assertEquals('stable', $secondFile->getState(3));
        self::assertEquals('edited', $secondFile->getState(4));
        self::assertEquals('edited', $secondFile->getState(5));
        self::assertEquals('stable', $secondFile->getState(6));
        self::assertEquals('add', $secondFile->getState(7));
        self::assertEquals('add', $secondFile->getState(8));
        self::assertEquals('add', $secondFile->getState(9));
        self::assertEquals('add', $secondFile->getState(10));
    }

    public function testEmptyFileCompare() : void
    {
        $firstFile = new ModifyTextDocument(new SafeTextDocument(
            FileReader::readTextDocument(__DIR__ . '/tmpFiles/unequalFirstCase.txt')));
        $secondFile = new ModifyTextDocument(new SafeTextDocument(
            FileReader::readTextDocument(__DIR__ . '/tmpFiles/emptyCase.txt')));

        Compiler::compare($firstFile, $secondFile);

        for ($i = 0; $i < $firstFile->getSize(); $i++)
        {
            self::assertEquals('delete', $firstFile->getState($i));
        }

        Compiler::compare($secondFile, $firstFile);

        for ($i = 0; $i < $firstFile->getSize(); $i++)
        {
            self::assertEquals('add', $firstFile->getState($i));
        }
    }
}