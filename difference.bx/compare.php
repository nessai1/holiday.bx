<?php
require_once (__DIR__ . '/header.php');
require_once (__DIR__ . '/Database/Manipulator.php');
require_once (__DIR__ . '/Document/ModifyTextDocument.php');
require_once (__DIR__ . '/Document/SafeTextDocument.php');
require_once (__DIR__ . '/Router.php');
require_once (__DIR__ . '/Compiler.php');


try
{
    if (isset($_GET['compareID'])) // если загружаем сравние из базы данных
    {
        $compareID = intval($_GET['compareID']);
        // записали в firstModFile и secondModFile объекты класса ModifyTextDocument из базы данных
        Manipulator::getData()->getCompareSession($compareID, $firstModifyDoc, $secondModifyDoc);
    }
    elseif (isset($_POST['loadFiles'])) // если загружаем файлы
    {
        if ($_FILES['firstFile']['error'] != 0 || $_FILES['secondFile']['error'] != 0)
        {
            throw new Exception("Can't load files on the server, errors: [{$_FILES['firstFile']['error']}:{$_FILES['secondFile']['error']}]", 6);
        }

        $firstTextDoc = new SafeTextDocument(FileReader::readTextDocument($_FILES['firstFile']['tmp_name']));
        $secondTextDoc = new SafeTextDocument(FileReader::readTextDocument($_FILES['secondFile']['tmp_name']));

        $firstTextDoc->setName($_FILES['firstFile']['name']);
        $secondTextDoc->setName($_FILES['secondFile']['name']);

        $firstModifyDoc = new ModifyTextDocument($firstTextDoc);
        $secondModifyDoc = new ModifyTextDocument($secondTextDoc);

        Compiler::compare($firstModifyDoc, $secondModifyDoc);
        Manipulator::setData()->addCompareFiles($firstModifyDoc, $secondModifyDoc);
    }
    elseif (isset($_POST['loadTexts'])) // если вводим текст
    {



        $firstTextArray = (strlen($_POST["firstText"]) == 0 ? [] : explode("\n", $_POST["firstText"]));
        $secondTextArray = (strlen($_POST["secondText"]) == 0 ? [] : explode("\n", $_POST["secondText"]));

        $firstTextDoc = new SafeTextDocument(new TextDocument($firstTextArray));
        $secondTextDoc = new SafeTextDocument(new TextDocument($secondTextArray));

        if (isset($_POST['firstName']))
        {
            $firstTextDoc->setName($_POST['firstName']);
        }

        if (isset($_POST['secondName']))
        {
            $secondTextDoc->setName($_POST['secondName']);
        }

        $firstModifyDoc = new ModifyTextDocument($firstTextDoc);
        $secondModifyDoc = new ModifyTextDocument($secondTextDoc);
        Compiler::compare($firstModifyDoc, $secondModifyDoc);
        Manipulator::setData()->addCompareFiles($firstModifyDoc, $secondModifyDoc);
    }
    else // если запрос ни с формы загрузки файлов, ни с формы текстовой, ни с базы данных
    {
        throw new Exception("Wrong input type", 5);
    }
}
catch (Exception $e)
{
    $logMessage = "[compare.php] {$e->getMessage()}";
    Logger::getInstance()->log($logMessage);
    Router::redirect('index.php', ['error' => $e->getCode()]);
}


?>

    <div class="compareResult">
        <h1 class="compareResult__title">Результат сравнения</h1>

        <div class="compareResult__tables">


            <div class="codeTable">
                <div class="codeTable__head">
                    <span><?=$firstModifyDoc->getName()?></span>
                    <span><span class="codeTable_linesCounter"><?=$firstModifyDoc->getSize()?></span> lines </span>
                </div>
                <div class="codeTable__code">
                    <table class="codeTable__table">
                        <?php
                            for ($i = 0; $i < $firstModifyDoc->getSize(); $i++)
                            { ?>
                                <tr class="codeTable_<?=$firstModifyDoc->getState($i)?>">
                                    <th class="codeTable__numLine"><?=$i+1?></th>
                                    <th class="codeTable__content"><?=$firstModifyDoc->getLine($i)?></th>
                                </tr>
                                <?php
                            }
                        ?>

                    </table>
                </div>
            </div>

            <div class="codeTable">
                <div class="codeTable__head">
                    <span><?=$secondModifyDoc->getName()?></span>
                    <span><span class="codeTable_linesCounter"><?=$secondModifyDoc->getSize()?></span> lines </span>
                </div>
                <div class="codeTable__code">
                    <table class="codeTable__table">
                        <?php
                        for ($i = 0; $i < $secondModifyDoc->getSize(); $i++)
                        { ?>
                            <tr class="codeTable_<?=$secondModifyDoc->getState($i)?>">
                                <th class="codeTable__numLine"><?=$i+1?></th>
                                <th class="codeTable__content"><?=$secondModifyDoc->getLine($i)?></th>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </div>
            </div>
            <!-- /compRes__tables -->
        </div>

    </div>

<?php
require_once (__DIR__ . '/bottom.php');