<?php

require_once (__DIR__ . '/DataReceiver.php');
require_once (__DIR__ . '/DataRecorder.php');

class Manipulator
{
    public static function getData()
    {
        return DataReceiver::getInstance(Database::getInstance());
    }

    public static function setData()
    {
        return DataRecorder::getInstance(Database::getInstance());
    }
}