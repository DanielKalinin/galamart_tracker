<?php

class CardLogger
{
    static function getHistory($cardid)
    {
        $db = DataBase::get();
        $querry = $db->prepare('SELECT * FROM card_log WHERE cardid=? ORDER BY date DESC');
        $querry->execute([$cardid]);
        return $querry->fetchAll();
    }
    
    static function log($cardid, $status)
    {
        $db = DataBase::get();
        if ('form_upd' != $status)
        {
            $querry = $db->prepare('SELECT status FROM card_log WHERE cardid=? ORDER BY date DESC LIMIT 1');
            $querry->execute([$cardid]);
            $old_status = $querry->fetch()['status'];
            if ($status == $old_status)
                return;
        }
        $querry = $db->prepare('INSERT INTO card_log (cardid, status) VALUES (?, ?)');
        $querry->execute([$cardid, $status]);
    }
}
