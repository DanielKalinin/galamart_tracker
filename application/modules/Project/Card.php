<?php

include_once 'Task.php';
include_once 'CardLogger.php';
include_once MOD . '/Form/Form.php';

class Card
{
    public $cardid;
    public $name;
    public $tasksid = [];
    public $formid;
    public $status;
    public $parentsid = [];
    public $childrenid = [];
    public $chatid;
    public $reactivated;

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

    public function __destruct()
    {
    }

    public function save()
    {
        $db = DataBase::get();
        $tasksid = implode_to_str(' ', $this->tasksid);
        $childrenid = implode_to_str(' ', $this->childrenid);
        $parentsid = implode_to_str(' ', $this->parentsid);

        if (empty($this->cardid))
        {
            $querry = $db->prepare('INSERT INTO card (name, tasksid, formid, status, parentsid, childrenid, chatid, reactivated) '
                    . 'VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
            $querry->execute([$this->name, $tasksid, $this->formid, $this->status, $parentsid, $childrenid, $this->chatid, $this->reactivated]);
        }
        else
        {
            $querry = $db->prepare('UPDATE card SET name=?, tasksid=?, formid=?, status=?, parentsid=?, childrenid=?, chatid=?, reactivated=? '
                    . 'WHERE cardid=? LIMIT 1');
            $querry->execute([$this->name, $tasksid, $this->formid, $this->status, $parentsid, $childrenid, $this->chatid,$this->reactivated, $this->cardid]);
        }
    }

    public function load($cardid)
    {
        $db = DataBase::get();
        $querry = $db->prepare('SELECT * FROM card WHERE cardid=? LIMIT 1');
        $querry->execute([$cardid]);
        $card = $querry->fetch();
        $card['tasksid'] = explode_to_int(' ', $card['tasksid']);
        $card['parentsid'] = explode_to_int(' ', $card['parentsid']);
        $card['childrenid'] = explode_to_int(' ', $card['childrenid']);
        $this->assign($card);
    }

    function assign($card)
    {
        $this->cardid = intval($card['cardid']);
        $this->name = $card['name'];
        $this->tasksid = $card['tasksid'];
        $this->status = $card['status'];
        $this->formid = intval($card['formid']);
        $this->parentsid = $card['parentsid'];
        $this->childrenid = $card['childrenid'];
        $this->reactivated = $card['reactivated'];
        $this->chatid = intval($card['chatid']);
    }

    public function delete()
    {
        foreach ($this->tasksid as $taskid)
        {
            $task = Dejavu::getObject('Task', $taskid);
            $task->delete();
        }

        $form = Dejavu::getObject('Form', $this->formid);
        $form->delete();

        $chat = Dejavu::getObject('Chat', $this->chatid);
        $chat->delete();

        $db = DataBase::get();
        $querry = $db->prepare('DELETE FROM card_log WHERE cardid=?');
        $querry->execute([$this->cardid]);

        Dejavu::removeObject('Card', $this->cardid);

        $db = DataBase::get();
        $querry = $db->prepare('DELETE FROM card WHERE cardid=? LIMIT 1');
        $querry->execute([$this->cardid]);
    }

    public function activate()
    {
        if ($this->status == 'active')
            return true;
        if ($this->сheckParents())
        {
            if($this->status=="done")
                $this->activateChildren ();
            else{
            $this->status = 'active';
            $this->activateTasks();
            CardLogger::log($this->cardid, 'active');
            }
            return true;
            
        }
        return false;
    }

    public function activateChildren()
    {
        foreach ($this->childrenid as $childid ){
            $child = Dejavu::getObject('Card', $childid);
            $child->activate();
        }
    }

    public function сheckParents()
    {
        foreach ($this->parentsid as $parentid)
        {
            $parent = Dejavu::getObject('Card', $parentid);
            if ($parent->status != 'done')
                return false;
        }
        return true;
    }

    public function activateTasks()
    {
        foreach ($this->tasksid as $taskid)
        {
            $task = Dejavu::getObject('Task', $taskid);
            $task->activate();
        }
    }


    public function isAppointment($type)
    {
        $task = Dejavu::getObject('Task', $this->tasksid[0]);
        $project = Dejavu::getObject('Project', $task->projectid);

        if ($task->status != 'in_progress')
            return false;

        if (mProject::typeInProject($project, $type))
            return false;

        if ($type == 'project_manager')
            return $this->name == 'Назначение руководителя проекта';
        else if ($type == 'visiting_coach')
            return $this->name == 'Назначение выездного тренера';
    }


    function isFinished()
    {
        if ($this->status == 'done')
            return true;
        return false;
    }
    //!Go there!//
    /*public function finishInstantly(){
        $_task= Dejavu::getObject("Task", (int)$this->childrenid[0]);
        $this->status = 'done';
        $this->reactivated='false';
        $this->save();
        $this->сheckChildren();
        $desk = new Desk($_task->deskid);
        $desk->finish($_task);
    }*/

    public function finish($_task)
    {
        if ($this->status != 'active')
            return;

        if (empty($this->tasksid))
        {
            $this->status = 'done';
            $this->reactivated='false';
            return;
        }
        foreach ($this->tasksid as $taskid)
        {
            $task = Dejavu::getObject('Task', $taskid);
            if (!$task->isFinished())
                return;
        }

        $this->status = 'done';
        $this->reactivated='false';
        $this->сheckChildren();
        $desk = Dejavu::getObject('Desk', $_task->deskid);
        $desk->finish($_task);
    }

    public function сheckChildren()
    {
        if (empty($this->childrenid))
        {
            return;
        }
        foreach ($this->childrenid as $childid)
        {
            $child = Dejavu::getObject('Card', $childid);
            $child->activate();
        }
    }

    function getFormInputNames()
    {
        preg_match_all('#<input.*?name="(.*?)"#', $this->formid, $matches, PREG_PATTERN_ORDER);
        $names = [];
        foreach ($matches[1] as $name)
        {
            $names[$name] = $name;
        }
        return $names;
    }

    function addValueToForm($name, $value)
    {
        if (preg_match('#<input type="text" name="' . $name . '" value="(.*?)"#', $this->formid))
        {
            $this->formid = preg_replace(['#name="' . $name . '" value="(.*?)"#'], ['name="' . $name . '" value="' . $value . '"'], $this->formid);
        }
        else if (preg_match('#<input type="file" name="' . $name . '"#', $this->formid))
        {
            $this->formid = preg_replace(['#<a name="' . $name . '" href=".*?">.*?</a>#'], ['<a name="' . $name . '" href="' . $value . '">' . $value . '</a>'], $this->formid);
        }
        else if (preg_match('#<input type="radio" name="' . $name . '"#', $this->formid))
        {
            //???????????????????????
            $this->formid = preg_replace(['#<input type="radio" name="' . $name . '" value="' . $value . '"#'], ['<input type="radio" name="' . $name . '" value="' . $value . '" checked'], $this->formid);
        }
        else if (preg_match('#<select name="' . $name . '"#', $this->formid))
        {
            //???????????????????????????????????
        }

    }

    function progress()
    {
        return $this->status == 'done';
    }

    function isAvail($usertype)
    {
        foreach ($this->tasksid as $taskid)
        {
            $task = Dejavu::getObject('Task', $taskid);
            if ($task->isAvail($usertype))
                return true;
        }
    }

}
