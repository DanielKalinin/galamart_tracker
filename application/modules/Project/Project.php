<?php

include_once 'Stage.php';

class Project
{

    public $projectid;
    public $cityid;
    public $stagesid = [];
    public $current_stage;
    public $usersid = [];
    public $startdate;
    public $finished;

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

    function load($projectid)
    {
        $db = DataBase::get();
        $querry = $db->prepare('SELECT * FROM project WHERE projectid=? LIMIT 1');
        $querry->execute([$projectid]);
        $project = $querry->fetch();
        $this->assign($project);
    }

    function assign($project)
    {
        $this->projectid = intval($project['projectid']);
        $this->cityid = intval($project['cityid']);
        $this->stagesid = explode_to_int(' ', $project['stagesid']);
        $this->current_stage = intval($project['current_stage']);
        $this->usersid = explode_to_int(' ', $project['usersid']);
        $this->startdate = $project['startdate'];
        $this->finished = $project['finished'];
    }

    public function save()
    {
        $db = DataBase::get();
        $stagesid = implode_to_str(' ', $this->stagesid);
        $usersid = implode_to_str(' ', $this->usersid);

        if (empty($this->stagesid))
        {
            $querry = $db->prepare('INSERT INTO project (cityid, stagesid, current_stage, usersid, startdate, finished)'
                    . 'VALUES (?, ?, ?, ?, ?, ?)');
            $querry->execute([$this->cityid, $stagesid, $this->current_stage,
                $usersid, $this->startdate, $this->finished]);
        }
        else
        {
            $querry = $db->prepare('UPDATE project SET cityid=?, stagesid=?, current_stage=?, usersid=?, startdate=?, finished=? '
                    . 'WHERE projectid=? LIMIT 1');
            $querry->execute([$this->cityid, $stagesid, $this->current_stage,
                $usersid, $this->startdate, $this->finished, $this->projectid]);
        }

    }

    public function delete()
    {
        foreach ($this->stagesid as $stageid)
        {
            $stage = Dejavu::getObject('Stage', $stageid);
            $stage->delete();
        }

        Dejavu::removeObject('Project', $this->projectid);

        $db = DataBase::get();
        $querry = $db->prepare('DELETE FROM project WHERE projectid=? LIMIT 1');
        $querry->execute([$this->projectid]);
    }

    function addUser($_userid)
    {
        foreach($this->usersid as $userid)
        {
            if ($userid == $_userid)
                return;
        }

        $this->usersid[] = $_userid;
    }

    function isFinished()
    {
        return $this->finished;
    }

    public function finish()
    {
        if ($this->finished)
            return;
        if ($this->stagesid == [])
        {
            $this->finished = true;
            return;
        }

        $this->nextStage();

        $db = DataBase::get();
        $stageSize = count($this->stagesid);
        $querryStr = "SELECT COUNT(*) FROM stage WHERE status='done' AND stageid IN (?" .
                str_repeat(',?', $stageSize - 1) . ") LIMIT " . $stageSize;
        $querry = $db->prepare($querryStr);
        $querry->execute($this->stagesid);
        $gg = $querry->fetch()['COUNT(*)'];
        $this->finished = ($stageSize == $gg);
        return;
    }

    public function getStage($index)
    {
        return Dejavu::getObject('Stage', $this->stagesid[$index]);
    }

    public function getCurrentStage()
    {
        return Dejavu::getObject('Stage', $this->stagesid[$this->current_stage]);
    }

    public function nextStage()
    {
        if (count($this->stagesid) > $this->current_stage + 1)
        {
            $stage = $this->getCurrentStage();

            if ($stage->isFinished())
            {
                $this->current_stage++;
                $stage = $this->getCurrentStage();
                $stage->activate($this);
                return true;
            }
            else
            {
                return false;
            }
        }
        return false;
    }

    function progress()
    {
        $sum = 0;
        if (empty($this->stagesid))
            return 1;
        foreach ($this->stagesid as $stageid)
        {
            $stage = Dejavu::getObject('Desk', $stageid);
            $sum += $stage->progress();
        }
        return $sum / count($this->stagesid);
    }

    function isAvail($usertype)
    {
        foreach ($this->stagesid as $stageid)
        {
            $stage = Dejavu::getObject('Stage', $stageid);
            if ($stage->isAvail($usertype))
                return true;
        }
        return false;
    }

}
