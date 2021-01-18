<?php
require_once (__DIR__ . '/header.php');
?>

<div class="indexLinks">
    <h1 class="indexLinks__header">Добро пожаловать</h1>
    <div class="indexLinks__links">
        <button class="button" id="fileButton" onclick="changeFileForm()">Сравнить файлы</button>
        <button class="button" id="textButton" onclick="changeTextForm()">Сравнить текст</button>
    </div>

    <form action="" class="loadForm" id="fileMenu" style="display: none">
        <div class="loadForm__files">
            <div class="loadForm__file">
            <h3 class="loadForm__title">Before file</h3>
            <input type="file">
            </div>
            <div class="loadForm__file">
            <h3 class="loadForm__title">After file</h3>
            <input type="file">
            </div>
        </div>
        <button type="submit" class="button" style="margin-top: 70px">Сравнить!</button>
    </form>


    <form action="" class="loadForm" id="textMenu" style="display: none">
            <div class="areas">
            <div class="textInput">
                <input type="text" placeholder="first file name" class="textInput__filename">
                <textarea placeholder="Your text after" wrap="off" class="textInput__content" type="text"></textarea>
            </div>
            <div class="textInput">
                <input type="text" placeholder="second file name" class="textInput__filename">
            <textarea placeholder="Your text before" wrap="off" class="textInput__content" type="text"></textarea>
            </div>
            </div>
        <button type="submit" class="button loadForm__button" style="flex-direction: row">Сравнить!</button>
    </form>
</div>

<script src="js/openFormScript.js"></script>
<?php
require_once (__DIR__ . '/bottom.php');
