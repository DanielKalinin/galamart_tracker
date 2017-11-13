<?php

include_once 'Data.php';

class Message
{

    public $messageid;
    public $userid;
    public $datasid = [];
    public $checked;
    public $date;

    public function __construct($inputData)
    {
        if (is_int($inputData))
        {
            $this->load($inputData);
        }
        else
        {
            $this->userid = $inputData['userid'];
            $this->datasid = $inputData['datasid'];
        }
    }

    public function __destruct()
    {
    }

    public function save()
    {
        $db = DataBase::get();
        $datasid = implode_to_str(' ', $this->datasid);
        if (empty($this->messageid))
        {
            $querry = $db->prepare('INSERT INTO message (userid, datasid) VALUES (?, ?)');
            $querry->execute([$this->userid, $datasid]);
            $this->messageid = intval($db->lastInsertId());
        }
        else
        {
            $queery = $db->prepare('UPDATE message SET userid=?, datasid=?, checked=?  WHERE messageid=? LIMIT 1');
            $queery->execute([$this->userid, $datasid, $this->checked, $this->messageid]);
        }
    }

    public function load($messageid)
    {
        $db = DataBase::get();
        $querry = $db->prepare('SELECT * FROM message WHERE messageid=? LIMIT 1');
        $querry->execute([$messageid]);
        $message = $querry->fetch();
        $this->messageid = intval($message['messageid']);
        $this->date = $message['date'];
        $this->userid = intval($message['userid']);
        $this->checked = $message['checked'];
        $this->datasid = explode_to_int(' ', $message['datasid']);
    }

    public function delete()
    {
        foreach ($this->datasid as $dataid)
        {
            $data = Dejavu::getObject('Data', $dataid);
            $data->delete();
        }

        Dejavu::removeObject('Message', $this->messageid);

        $db = DataBase::get();
        $querry = $db->prepare('DELETE FROM message WHERE messageid=? LIMIT 1');
        $querry->execute([$this->messageid]);
    }

    public function addData($dataid)
    {
        if (in_array($dataid, $datasid))
            return;
        
        $this->datasid[] = $dataid;
    }

    public function deleteData($dataid)
    {
        unset($this->datasid[$dataid]);
    }

    public function getAllDatas()
    {
        $datas = [];
        foreach ($this->datasid as $dataid)
        {
            $datas[] = Dejavu::getObject('Data', $dataid);
        }
        return $datas;
    }

    public function check()
    {
        $this->checked = true;
    }

}
