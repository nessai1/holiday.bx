<?php

use PHPUnit\Framework\TestCase;

include_once (__DIR__ . "/../../difference.bx/Document/ModifyTextDocument.php");
include_once (__DIR__ . "/../../difference.bx/Exceptions/WrongIndexException.php");

class ModifyTextDocumentTest extends TestCase
{
    public function testWrongIndexLine() : void
    {
        $testLines = ["Hello world", "Hello PHP"];
        $testTextDoc = new TextDocument($testLines);
        $testModifyDoc = new ModifyTextDocument($testTextDoc);

        self::expectException(WrongIndexException::class);

        $testModifyDoc->getState(2);
    }

    public function testDefaultState() : void
    {
        $testLines = ["Hello world", "Hello PHP"];
        $testTextDoc = new TextDocument($testLines);
        $testModifyDoc = new ModifyTextDocument($testTextDoc);

        self::assertEquals("stable", $testModifyDoc->getState(1));
    }

    public function testWrongIndexSetState() : void
    {
        $testLines = ["Hello world", "Hello PHP"];
        $testTextDoc = new TextDocument($testLines);
        $testModifyDoc = new ModifyTextDocument($testTextDoc);

        self::expectException(WrongIndexException::class);

        $testModifyDoc->setState(3, "add");
    }

    public function testChangeState() : void
    {
        $testLines = ["Hello world", "Hello PHP"];
        $testTextDoc = new TextDocument($testLines);
        $testModifyDoc = new ModifyTextDocument($testTextDoc);

        $testModifyDoc->setState(1, "add");

        self::assertEquals("add",$testModifyDoc->getState(1));
    }
    
    public function testEqualLines() : void
    {
        $testLines = ["Hello world", "Hello PHP"];
        $testTextDoc = new TextDocument($testLines);
        $testModifyDoc = new ModifyTextDocument($testTextDoc);
        
        self::assertEquals($testTextDoc->getLine(1), $testModifyDoc->getLine(1));
    }
}