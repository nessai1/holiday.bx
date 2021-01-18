<?php
require_once (__DIR__ . '/header.php');
require_once (__DIR__ . '/FileReader.php');
?>

<div class="indexLinks" style="align-items: center">
    <?php

    if (isset($_GET['error']))
    {
        try
        {
            $errors = FileReader::readJSON(__DIR__.'/config.json', 'Errors');
            if (isset($errors[$_GET['error']]))
            {
                $message = $errors[$_GET['error']];
            }
            else
            {
                $message = "An unexpected error occurred";
            }
        }
        catch (Exception $e)
        {
            $message = "Произошла серьезная ошибка в чтении файла конфигурации.";
        }
    }

    ?>

    <?php
    if (!isset($_GET['error']))
        {
            ?><h1 class="indexLinks__header">Добро пожаловать</h1><?php
        }
    else
        {
            ?><div class="errorMessage"><?=$message?></div>
    <?php
        }
    ?>

    <div class="indexLinks__links">
        <button class="button" id="fileButton" onclick="changeFileForm()">Сравнить файлы</button>
        <button class="button" id="textButton" onclick="changeTextForm()">Сравнить текст</button>
    </div>

    <form method="POST" action="compare.php" class="loadForm" id="fileMenu" enctype="multipart/form-data" style="display: none">
        <div class="loadForm__files">
            <div class="loadForm__file">
            <h3 class="loadForm__title">Before file</h3>
            <input type="file" name="firstFile" required>
            </div>
            <div class="loadForm__file">
            <h3 class="loadForm__title">After file</h3>
            <input type="file" name="secondFile" required>
            </div>
        </div>
        <button type="submit" class="button" style="margin-top: 70px" name="loadFiles">Сравнить!</button>
    </form>


    <form method="POST" action="compare.php" class="loadForm" id="textMenu" style="display: none">
            <div class="areas">
            <div class="textInput">
                <input type="text" placeholder="first file name" class="textInput__filename" value="FirstFile.txt" name="firstName">
                <textarea placeholder="Your text after" wrap="off" class="textInput__content" type="text" name="firstText"></textarea>
            </div>
            <div class="textInput">
                <input type="text" placeholder="second file name" class="textInput__filename" value="SecondFile.txt" name="secondName">
            <textarea placeholder="Your text before" wrap="off" class="textInput__content" type="text" name="secondText"></textarea>
            </div>
            </div>
        <button type="submit" class="button loadForm__button" style="flex-direction: row" name="loadTexts">Сравнить!</button>
    </form>
</div>

<script src="js/openFormScript.js"></script>
<?php
require_once (__DIR__ . '/bottom.php');
