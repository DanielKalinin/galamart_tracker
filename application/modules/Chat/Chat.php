<?php

include_once 'Message.php';

class Chat
{

    public $chatid;
    public $messagesid = [];
    public $usersid = [];

    function __construct($initData)
    {
        if (is_int($initData))
            $this->loadId($initData);
        else if (is_array($initData))
            $this->loadArray($initData);
        else
            throw Dejavu::getObject('Exception', 'Bad initialization data in Chat constructor');
    }

    function loadId($chatid)
    {
        $db = DataBase::get();
        $querry = $db->prepare("SELECT * FROM chat WHERE chatid=? LIMIT 1");
        $querry->execute([$chatid]);
        $chat = $querry->fetch();
        $this->assign($chat);
    }

    function loadArray($array)
    {
        if (isset($array['chatid']))
            $this->assign($array);
        else
            $this->create($array);
    }

    function assign($chat)
    {
        $this->chatid = intval($chat['chatid']);

        if (is_string($chat['messagesid']))
            $this->messagesid = explode_to_int(' ', $chat['messagesid']);
        else
            $this->messagesid = $chat['messagesid'];

         if (is_string($chat['usersid']))
            $this->usersid = explode_to_int(' ', $chat['usersid']);
        else
            $this->usersid = $chat['usersid'];
    }

    function create($data)
    {
        $this->saveEmpty();
        $this->chatid = $this->getLastId();
        if(isset($data['usersid']))
            $this->usersid=$data['usersid'];
        if(isset($data['messagesid']))
            $this->messagesid=$data['messagesid'];

    }

    function delete()
    {
        foreach($this->messagesid as $messageid)
        {
            $message = Dejavu::getObject('Message', $messageid);
            $message->delete();
        }

        Dejavu::removeObject('Chat', $this->chatid);

        $db = DataBase::get();
        $querry = $db->prepare('DELETE FROM chat WHERE chatid=? LIMIT 1');
        $querry->execute([$this->chatid]);
    }

    private function saveEmpty()
    {
        $db = DataBase::get();
        $querry = $db->prepare('INSERT INTO chat(messagesid,usersid) VALUES ("","")');
        $querry->execute([]);
    }

    private function getLastId()
    {
        $db = DataBase::get();
        return intval($db->lastInsertId());
    }

    function save()
    {
        $messagesid = implode_to_str(' ', $this->messagesid);
        $usersid = implode_to_str(' ', $this->usersid);

        $db = DataBase::get();
        $querry = $db->prepare('UPDATE chat SET messagesid=?, usersid=? WHERE chatid=? LIMIT 1');
        $querry->execute([$messagesid,$usersid, $this->chatid]);
    }

    function isEmpty()
    {
        return empty($this->messagesid);
    }


    public function addUser($userid)
    {
        foreach ($this->usersid as $key => $user)
        {
            if ($user == $userid)
                return;
        }
        $this->usersid[] = $userid;
    }

    public function deleteUser($userid)
    {
        foreach ($this->usersid as $key => $user)
        {
            if ($user == $userid)
                unset($this->usersid[$key]);
        }
    }

    public function checkAccess($userid)
    {
        foreach ($this->usersid as $key => $user)
        {
            if ($user == $userid)
                return true;
        }
        return false;
    }

    public function addMessage($messageid)
    {
        $this->messagesid[] = $messageid;
    }

    public function deleteMessage($messageid)
    {
        foreach ($this->messagesid as $key => $message)
        {
            if ($message == $messageid)
                unset($this->messagesid[$key]);
        }
    }

    public function getAllMessages()
    {

        $messages = [];
        foreach ($this->messagesid as $messageid)
        {

            $messages[] = Dejavu::getObject('Message', $messageid);
        }
        return $messages;
    }

    public function getMessages($from, $to)
    {
        $messages = [];
        for ($k = $from; $k <= $to; $k++)
        {

            $messages[] = Dejavu::getObject('Message', $this->messagesid[$k]);
        }
        return $messages;
    }

}
