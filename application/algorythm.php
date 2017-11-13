<?php

function explode_to_int($delimiter, $array_str)
{
    if ($array_str == '')
        return [];
    else
        return array_map('intval', explode($delimiter, $array_str));
}

function implode_to_str($delimiter, $array)
{
    if ($array == [])
        return '';
    else
        return implode($delimiter, array_map('strval', $array));
}

function unauthorized()
{
    session_start();
    if (!$_SESSION['unauthirized'])
    {
        $_SESSION['unauthirized'] = true;
        $_SESSION['URI'] = URI;
    }
    header('Location: /authorization');
}

function authorized()
{
    session_start();
    session_unset();
    session_destroy();
    session_commit();
}

function sendmail($mail, $title, $msg)
{
    mail($mail, $title, $msg,
            "From: registration@franchgala.tk\t\n"
            . "Content-Type: text/html; charset=utf-8\r\n");
}

function clearAll()
{
    return;
    //$db = DataBase::get();
    //$querry = $db->prepare('DELETE FROM project; DELETE FROM stage; DELETE FROM desk; DELETE FROM card; '
    //        . 'DELETE FROM chat; DELETE FROM task; UPDATE user SET projectsid=\'\';');
    //$querry->execute();
}

function timeFormating($date){
    $year=$date[0].$date[1].$date[2].$date[3];
    $month=$date[5].$date[6];
    $day=$date[8].$date[9];
    $hours=$date[11].$date[12];
    $minutes=$date[14].$date[15];
    return $hours.':'.$minutes.' '.$day.'.'.$month.'.'.$year;
}


