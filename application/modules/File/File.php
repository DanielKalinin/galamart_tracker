<?php

class File
{
    var $fileid;
    var $name;
    var $type;
    var $size;
    var $data;

    function __construct($initData)
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

    function load($fileid)
    {
        $db = DataBase::get();
        $querry = $db->prepare('SELECT * FROM file WHERE fileid=? LIMIT 1');
        $querry->execute([$fileid]);
        $file = $querry->fetch();
        $this->assign($file);
    }

    function delete()
    {

    }

    function assign($file)
    {
        $this->fileid = intval($file['fileid']);
        $this->name = $file['name'];
        $this->type = $file['type'];
        $this->size = intval($file['size']);
        $this->data = $file['data'];
    }


    function save()
    {
        $db = DataBase::get();
        if (empty($this->fileid))
        {
            $querry = $db->prepare('INSERT INTO file (name, type, size, data) VALUES (?, ?, ?, ?)');
            $querry->execute([$this->name, $this->type, $this->size, $this->data]);
            $this->fileid = intval($db->lastInsertId());
        }
        else
        {
            $querry = $db->prepare('UPDATE file SET name=?, type=?, size=?, data=? WHERE fileid=?');
            $querry->execute([$this->name, $this->type, $this->size, $this->data, $this->fileid]);
        }
    }
}
