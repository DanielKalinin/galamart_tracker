<?php

$db = new PDO('mysql:dbname=cp66918_test;host=localhost', 'cp66918_test', 'sescurfu282');

$querry = $db->prepare("SELECT * FROM message WHERE NOT checked");
$querry->execute();
$messages = $querry->fetchAll();
foreach ($messages as $message)
{
    $querry = $db->prepare("SELECT executorid, verifierid, cardid FROM card WHERE "
            . "chatid=(SELECT chatid FROM chat WHERE messagesid LIKE '%{$message['messageid']}%' LIMIT 1) LIMIT 1");
    $querry->execute();
    $fetch = $querry->fetch();
    $querry = $db->prepare("SELECT projectid, stageid, deskid, cardid FROM task WHERE cardid={$fetch['cardid']} LIMIT 1");
    $querry->execute();
    $url = $querry->fetch();
    if ($fetch['verifierid'] == $message['userid'])
        $querry = $db->prepare("SELECT mail FROM user WHERE userid={$fetch['executorid']}");
    else
        $querry = $db->prepare("SELECT mail FROM user WHERE userid={$fetch['verifierid']}");
    $querry->execute();
    $receiver = $querry->fetch()['mail'];
    $href = "http://franchgala.tk/projects/{$url['projectid']}/{$url['stageid']}/{$url['deskid']}/{$url['cardid']}";
    $datasid = implode(',', explode(' ', $message['datasid']));
    $querry = $db->prepare("SELECT data FROM data WHERE dataid IN ($datasid) AND type='text' LIMIT 1");
    $querry->execute();
    $text = $querry->fetch()['data'];
    mail($receiver, "Сообщение",
            "Непрочитанное сообщение в карточке <a href='$href'>$href</a>:\n$text",
            "From: notification@franchgala.tk\t\n"
            . "Content-Type: text/html; charset=utf-8\r\n");
}