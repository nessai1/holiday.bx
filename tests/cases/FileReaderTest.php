<?php

use PHPUnit\Framework\TestCase;
require_once(__DIR__ . '/../../difference.bx/FileReader.php');

class FileReaderTest extends TestCase
{
    public function testNonExistingFile() : void
    {
        self::expectException(PathNotFoundException::class);
        FileReader::readTextDocument("blahblahblah");
    }

    public function testReachableFile() : void
    {
        $testString = "Hello World".PHP_EOL."Hello PHP";
        $tmpDir = __DIR__."/tmpFiles/testReachableFileCase.tmp";

        $testTextDoc = new TextDocument(explode(PHP_EOL, $testString));

        $testFile = fopen($tmpDir,"w");
        fwrite($testFile, $testString);
        fclose($testFile);

        $testTextDocFromFile = FileReader::readTextDocument($tmpDir);
        self::assertEquals($testTextDoc, $testTextDocFromFile);

        unlink($tmpDir);
    }

    public function testUnequalFiles() : void
    {
        $firstTmpDir = __DIR__."/tmpFiles/testUnequalFileCaseFIRST.tmp";
        $secondTmpDir = __DIR__."/tmpFiles/testUnequalFileCaseSECOND.tmp";

        $firstTestFile = fopen($firstTmpDir, "w");
        fwrite($firstTestFile, "hello world1");
        fclose($firstTestFile);

        $secondTestFile = fopen($secondTmpDir, "w");
        fwrite($secondTestFile, "hello world2");
        fclose($secondTestFile);

        $firstTestTextDoc = FileReader::readTextDocument($firstTmpDir);
        $secondTestTextDoc = FileReader::readTextDocument($secondTmpDir);

        self::assertNotEquals($firstTestTextDoc, $secondTestTextDoc);

        unlink($firstTmpDir);
        unlink($secondTmpDir);
    }

    public function testEmptyFile() : void
    {
        $testTextDoc = new TextDocument(array());

        $tmpDir = __DIR__."/tmpFiles/testEmptyFileCase.tmp";

        $testFile = fopen($tmpDir, "w");
        fclose($testFile);

        $testTextDocFromFile = FileReader::readTextDocument($tmpDir);

        self::assertEquals($testTextDocFromFile, $testTextDoc);
    }

    public function testEmptyAndNotEmptyFiles() : void
    {
        $firstTmpDir = __DIR__."/tmpFiles/testEmptyAndNotEmptyFilesCaseFIRST.tmp"; // empty
        $secondTmpDir = __DIR__."/tmpFiles/testEmptyAndNotEmptyFilesCaseSECOND.tmp";

        $firstTestFile = fopen($firstTmpDir, "w");
        fclose($firstTestFile);

        $secondTestFile = fopen($secondTmpDir, "w");
        fwrite($secondTestFile, "hello world");
        fclose($secondTestFile);

        $firstTestTextDoc = FileReader::readTextDocument($firstTmpDir);
        $secondTestTextDoc = FileReader::readTextDocument($secondTmpDir);

        self::assertNotEquals($firstTestTextDoc, $secondTestTextDoc);

        unlink($firstTmpDir);
        unlink($secondTmpDir);
    }
}