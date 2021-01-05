<?php

use PHPUnit\Framework\TestCase;
require_once(__DIR__ . '/../../difference.bx/Document/SafeTextDocument.php');

class SafeTextDocumentTest extends TestCase
{
    public function testNonScriptFile() : void
    {
        $testText = "Hello world";
        $firstTestFile = new TextDocument([$testText]); // non safe file
        $secondTestFile = new SafeTextDocument(new TextDocument([$testText])); // safe file
        self::assertEquals($firstTestFile->getLine(0), $secondTestFile->getLine(0));
    }

    public function testScriptFile() : void
    {
        $testText = "<script>alert('XSS')</script>";
        $firstTestFile = new TextDocument([$testText]); // non safe file
        $secondTestFile = new SafeTextDocument(new TextDocument([$testText])); // safe file
        self::assertNotEquals($firstTestFile->getLine(0), $secondTestFile->getLine(0));
    }

    public function testNonScriptFileName() : void
    {
        $testText = "Hello world";
        $testName = "s0me_s3cr3t.txt"; // safe name
        $firstTestFile = new TextDocument([$testText], $testName); // non safe file
        $secondTestFile = new SafeTextDocument(new TextDocument([$testText], $testName)); // safe file
        self::assertEquals($firstTestFile->getName(), $secondTestFile->getName());
    }

    public function testScriptFileName() : void
    {
        $testText = "Hello world";
        $testName = "<script>alert('XSS')</script>"; // non safe name
        $firstTestFile = new TextDocument([$testText], $testName); // non safe file
        $secondTestFile = new SafeTextDocument(new TextDocument([$testText], $testName)); // safe file
        self::assertNotEquals($firstTestFile->getName(), $secondTestFile->getName());
    }
}