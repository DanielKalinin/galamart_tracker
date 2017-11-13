<?php

function suffix($number, $mas)
{
    //сначала для 0, потом для 1, потом для 2
    $number = (int)$number;
    $kek = $number % 100;
    if ($kek > 9 && $kek < 20)
        return $mas[0];
    $kek = $number % 10;
    if ($kek == 1)
        return $mas[1];
    if ($kek > 1 && $kek < 5)
        return $mas[2];
    return $mas[0];
}
