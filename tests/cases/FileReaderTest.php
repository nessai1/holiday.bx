<?php

use PHPUnit\Framework\TestCase;
require_once(__DIR__ . '/../../difference.bx/FileReader.php');

class FileReaderTest extends TestCase
{
    public function testNonExistingFile() : void
    {
        self::expectException(PathNotFoundException::class);
        FileReader::readPath("blahblahblah");
    }

    public function testReachableFile() : void
    {
        $testString = "Hello World".PHP_EOL."Hello PHP";
        $tmpDir = __DIR__."/tmpFiles/testReachableFileCase.tmp";

        $testTextDoc = new TextDocument(explode(PHP_EOL, $testString));

        $testFile = fopen($tmpDir,"w");
        fwrite($testFile, $testString);
        fclose($testFile);

        $testTextDocFromFile = FileReader::readPath($tmpDir);
        self::assertEquals($testTextDoc, $testTextDocFromFile);

        unlink($tmpDir);
    }
}