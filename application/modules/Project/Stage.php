<?php

include_once 'Desk.php';

class Stage
{

    public $stageid;
    public $name;
    public $desksid = [];
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

    public function __destruct()
    {
    }

    public function save()
    {
        $db = DataBase::get();
        $desksid = implode_to_str(' ', $this->desksid);
        if (empty($this->stageid))
        {
            $querry = $db->prepare('INSERT INTO stage (name, desksid, status) '
                    . 'VALUES (?, ?, ?)');
            $querry->execute([$this->name, $desksid, $this->status]);
        }
        else
        {
            $querry = $db->prepare('UPDATE stage SET name=?, desksid=?, status=? '
                    . 'WHERE stageid=? LIMIT 1');
            $querry->execute([$this->name, $desksid, $this->status, $this->stageid]);
        }
    }

    public function load($stageid)
    {
        $db = DataBase::get();
        $querry = $db->prepare('SELECT * FROM stage WHERE stageid=? LIMIT 1');
        $querry->execute([$stageid]);
        $stage = $querry->fetch();
        $stage['desksid'] = explode_to_int(' ', $stage['desksid']);
        $this->assign($stage);
    }

    function assign($stage)
    {
        $this->stageid = intval($stage['stageid']);
        $this->name = $stage['name'];
        $this->desksid = $stage['desksid'];
        $this->status = $stage['status'];
    }

    public function delete()
    {
        foreach ($this->desksid as $deskid)
        {
            $desk = Dejavu::getObject('Desk', $deskid);
            $desk->delete();
        }

        Dejavu::removeObject('Stage', $this->stageid);

        $db = DataBase::get();
        $querry = $db->prepare('DELETE FROM stage WHERE stageid=? LIMIT 1');
        $querry->execute([$this->stageid]);
    }

    function activate($project)
    {
        if ($this->status == 'active')
            return true;
        foreach ($this->desksid as $deskid)
        {
            $desk = Dejavu::getObject('Desk', $deskid);
            $desk->activate($project);
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

        if (empty($this->desksid))
        {
            $this->status = 'done';
            $this->save();
            return;
        }
        foreach ($this->desksid as $deskid)
        {
            $desk = Dejavu::getObject('Desk', $deskid);
            if (!$desk->isFinished())
                return;
        }
        $this->status = 'done';
        $this->save();
        $project = Dejavu::getObject('Project', $_task->projectid);
        $project->finish();
        return;
    }

    function progress()
    {
        $sum = 0;
        if (empty($this->desksid))
            return 1;
        foreach ($this->desksid as $deskid)
        {
            $desk = Dejavu::getObject('Desk', $deskid);
            $sum += $desk->progress();
        }
        return $sum / count($this->desksid);
    }

    function isAvail($usertype)
    {
        foreach ($this->desksid as $deskid)
        {
            $desk = Dejavu::getObject('Desk', $deskid);
            if ($desk->isAvail($usertype))
                return true;
        }
    }

}
