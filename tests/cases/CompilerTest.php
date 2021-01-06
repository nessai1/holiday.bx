<?php

use PHPUnit\Framework\TestCase;

include_once (__DIR__ . "/../../difference.bx/Compiler.php");

class CompilerTest extends TestCase
{
    public function testEqualFiles() : void
    {
        $fileLines = ['Hello world', 'Hello PHP', 'How are you?'];
        $firstFile = new ModifyTextDocument(new SafeTextDocument(new TextDocument($fileLines)));
        $secondFile = new ModifyTextDocument(new SafeTextDocument(new TextDocument($fileLines)));
        Compiler::compare($firstFile, $secondFile);

        for ($i = 0; $i < count($fileLines); $i++)
        {
            self::assertEquals('stable', $firstFile->getState($i));
            self::assertEquals('stable', $firstFile->getState($i));
        }
    }

    public function testUnequalFiles() : void
    {
        $beforeLines = ['Hello world', 'Hello PHP'];
        $afterLines = ['Hello world', 'test message', 'Hello PHP'];
        $firstFile = new ModifyTextDocument(new SafeTextDocument(new TextDocument($beforeLines)));
        $secondFile = new ModifyTextDocument(new SafeTextDocument(new TextDocument($afterLines)));
        Compiler::compare($firstFile,$secondFile);

        for ($i = 0; $i < $firstFile->getSize(); $i++)
        {
            self::assertEquals('stable', $firstFile->getState($i));
        }

        self::assertEquals('stable', $secondFile->getState(0));
        self::assertEquals('add', $secondFile->getState(1));
        self::assertEquals('stable', $secondFile->getState(2));

    }
}