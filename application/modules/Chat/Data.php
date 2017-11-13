<?php

class Data
{

    public $dataid;
    public $type; //text, file
    public $data;

    public function __construct($inputData)
    {
        if (is_int($inputData))
        {
            $this->load($inputData);
        }
        else
        {
            $this->type = $inputData['type'];
            $this->data = $inputData['data'];
        }
    }

    public function __destruct()
    {
    }

    public function save()
    {
        $db = DataBase::get();
        if (empty($this->dataid))
        {
            $querry = $db->prepare('INSERT INTO data (data, type) VALUES (?, ?)');
            $querry->execute([$this->data, $this->type]);
            $this->dataid = intval($db->lastInsertId());
        }
        else
        {
            $queery = $db->prepare('UPDATE data SET data=?, type=? WHERE dataid=? LIMIT 1');
            $queery->execute([$this->data, $this->type, $this->dataid]);
        }
    }

    public function load($dataid)
    {
        $db = DataBase::get();
        $querry = $db->prepare('SELECT * FROM data WHERE dataid=? LIMIT 1');
        $querry->execute([$dataid]);
        $datum = $querry->fetch();
        $this->dataid = intval($datum['dataid']);
        $this->type = $datum['type'];
        $this->data = $datum['data'];
    }

    public function delete()
    {
        Dejavu::removeObject('Data', $this->dataid);

        $db = DataBase::get();
        $querry = $db->prepare('DELETE FROM data WHERE dataid=? LIMIT 1');
        $querry->execute([$this->dataid]);
    }

}
