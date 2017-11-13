<?php

include_once 'Card.php';

class Desk
{

    public $deskid;
    public $name;
    public $cardsid = [];
    public $status;

    public function __construct($initData)
    {
        if (is_int($initData))
        {
            $this->load($initData);
        }
        else
        {
            $this->assign($initData);
        }
    }

    public function save()
    {
        $db = DataBase::get();
        $cardsid = implode_to_str(' ', $this->cardsid);
        if (empty($this->deskid))
        {
            $querry = $db->prepare('INSERT INTO desk (name, cardsid, status) '
                    . 'VALUES (?, ?, ?)');
            $querry->execute([$this->name, $cardsid, $this->status]);
        }
        else
        {
            $querry = $db->prepare('UPDATE desk SET name=?, cardsid=?, status=? '
                    . 'WHERE deskid=? LIMIT 1');
            $querry->execute([$this->name, $cardsid, $this->status, $this->deskid]);
        }
    }

    public function load($deskid)
    {
        $db = DataBase::get();
        $querry = $db->prepare('SELECT * FROM desk WHERE deskid=? LIMIT 1');
        $querry->execute([$deskid]);
        $desk = $querry->fetch();
        $desk['cardsid'] = explode_to_int(' ', $desk['cardsid']);
        $this->assign($desk);
    }

    function assign($desk)
    {
        $this->deskid = intval($desk['deskid']);
        $this->name = $desk['name'];
        $this->cardsid = $desk['cardsid'];
        $this->status = $desk['status'];
    }

    public function delete()
    {
        foreach ($this->cardsid as $cardid)
        {
            $card = Dejavu::getObject('Card', $cardid);
            $card->delete();
        }

        Dejavu::removeObject('Desk', $this->deskid);

        $db = DataBase::get();
        $querry = $db->prepare('DELETE FROM desk WHERE deskid=? LIMIT 1');
        $querry->execute([$this->deskid]);
    }

    function activate($project)
    {
        if ($this->status == 'active')
            return true;
        foreach ($this->cardsid as $cardid)
        {
            $card = Dejavu::getObject('Card', $cardid);
            $card->activate($project);
        }
        $this->status = 'active';
        return true;
    }

    function isFinished()
    {
        if ($this->status == 'done')
            return true;
        return false;
    }

    public function finish($_task)
    {
        if ($this->status != 'active')
            return;

        if (empty($this->cardsid))
        {
            $this->status = 'done';
            return;
        }
        foreach ($this->cardsid as $cardid)
        {
            $card = Dejavu::getObject('Card', $cardid);
            if (!$card->isFinished())
                return;
        }
        $this->status = 'done';
        $stage = Dejavu::getObject('Stage', $_task->stageid);
        $stage->finish($_task);
    }

    function progress()
    {
        $sum = 0;
        if (empty($this->cardsid))
            return 1;
        foreach ($this->cardsid as $cardid)
        {
            $card = Dejavu::getObject('Card', $cardid);
            $sum += $card->progress();
        }
        return $sum / count($this->cardsid);
    }

    function isAvail($usertype)
    {
        foreach ($this->cardsid as $cardid)
        {
            $card = Dejavu::getObject('Card', $cardid);
            if ($card->isAvail($usertype))
                return true;
        }
    }

}
