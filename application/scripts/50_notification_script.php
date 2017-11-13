<?php

$db = new PDO('mysql:dbname=cp66918_franch;host=localhost', 'cp66918_franch', 'sescurfu282');

$querry = $db->prepare("SELECT projectid, stageid, deskid, cardid, executorid, "
        . "exhours - FLOOR((UNIX_TIMESTAMP() - UNIX_TIMESTAMP(startdate)) / 3600) AS timeleft FROM task "
        . "WHERE status='active' AND "
        . "UNIX_TIMESTAMP() >= (UNIX_TIMESTAMP(startdate) + exhours * 1800) "
        . "AND NOT hnotified; "
        . "UPDATE task SET hnotified=1 WHERE status='active' AND "
        . "UNIX_TIMESTAMP() >= (UNIX_TIMESTAMP(startdate) + exhours * 1800) "
        . "AND NOT hnotified;");
$querry->execute();
$fetch = $querry->fetchAll();
$placeholders = Array();
foreach ($fetch as $task)
    $placeholders[] = $task['executorid'];
$querry = $db->prepare('SELECT mail FROM user WHERE userid = ' . implode(' OR userid = ', $placeholders));
$querry->execute();
$fetch_executor = $querry->fetchAll();
$size = count($fetch);
for ($i = 0; $i < $size; ++$i)
{
    $card = Dejavu::getObject('Card', $fetch[$i]['cardid']);
    $task = Dejavu::getObject('Task', $card->tasksid[0]);
    mail($fetch_executor[$i]['mail'], "Задание",
            "У вас осталось {$fetch[$i]['timeleft']} часов на выполнение задания {$task->text} ."
            . "<a href='franchgala.tk/projects/{$fetch[$i]['projectid']}/{$fetch[$i]['stageid']}/{$fetch[$i]['deskid']}/{$fetch[$i]['cardid']}/'>"
            . "franchgala.tk/projects/{$fetch[$i]['projectid']}/{$fetch[$i]['stageid']}/{$fetch[$i]['deskid']}/{$fetch[$i]['cardid']}/</a>",
            "From: notification@franchgala.tk\t\n"
            . "Content-Type: text/html; charset=utf-8\r\n");
}

$placeholders = Array();
foreach ($fetch as $task)
    $placeholders[] = $task['projectid'];
$querry = $db->prepare('SELECT mail FROM user WHERE userid IN (SELECT userid FROM task WHERE projectid IN ('.implode(',', $placeholders).') AND '
        . '(verifiertype="project_manager" OR executortype="project_manager"))');
$querry->execute();
$fetch_pm = $querry->fetchAll();
    mail($fetch_pm[$i]['mail'], "Задание",
            "У исполнителя данного задание осталось {$fetch[$i]['timeleft']} часов на выполнение {$task->text} ."
            . "<a href='franchgala.tk/projects/{$fetch[$i]['projectid']}/{$fetch[$i]['stageid']}/{$fetch[$i]['deskid']}/{$fetch[$i]['cardid']}/'>"
            . "franchgala.tk/projects/{$fetch[$i]['projectid']}/{$fetch[$i]['stageid']}/{$fetch[$i]['deskid']}/{$fetch[$i]['cardid']}/</a>",
            "From: notification@franchgala.tk\t\n"
            . "Content-Type: text/html; charset=utf-8\r\n");