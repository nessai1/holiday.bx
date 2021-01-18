<?php
require_once (__DIR__ . '/header.php');
require_once (__DIR__ . '/Database/Manipulator.php');
require_once (__DIR__ . '/Router.php');

try {
    $allCompares = Manipulator::getData()->getAllCompareSessionsInfo();
}
catch (Exception $e)
{
    Router::redirect('index.php', ['error' => "{$e->getCode()}"]);
    exit();
}

?>

<div class="history">
    <table class="historyTable">
        <tr class="historyTable__header">
            <th>ID</th>
            <th>Before name</th>
            <th>After name</th>
            <th>Date</th>
        </tr>

        <?php
        //var_dump($allCompares);
        for ($i = 0; $i < count($allCompares); $i++)
        {
            if ($i % 2)
            {
                ?>
                <tr onclick="document.location = 'compare.php?compareID=<?=$allCompares[$i]['ID']?>';"  class="historyTable__content">
                <?php
            }
            else
            {
                ?>
                <tr onclick="document.location = 'compare.php?compareID=<?=$allCompares[$i]['ID']?>';"  class="historyTable__content historyTable__content_second">
                <?php
            }
            ?>
            <td><?=$allCompares[$i]['ID']?></td>
            <td><?=$allCompares[$i]['FIRST_FILE']?></td>
            <td><?=$allCompares[$i]['SECOND_FILE']?></td>
            <td><?=$allCompares[$i]['COMPARE_DATE']?></td>
            </tr>
            <?php
        }
        ?>
    </table>
</div>
<?php
require_once (__DIR__ . '/bottom.php');
