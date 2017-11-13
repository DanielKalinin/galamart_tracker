<?php

class Task
{

    public $taskid;
    public $cardid;
    public $deskid;
    public $stageid;
    public $projectid;
    public $executortype;
    public $verifiertype;
    public $executorid;
    public $verifierid;
    public $text;
    public $status;
    public $startdate;
    public $exhours;
    public $verhours;

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

        if (empty($this->taskid))
        {
            $querry = $db->prepare('INSERT INTO task (cardid, deskid, stageid, projectid, '
                    . 'executortype, verifiertype, executorid, verifierid, '
                    . 'text, status, startdate, exhours, verhours) '
                    . 'VALUES (?, ?, ?, ?, ?, ?)');
            $querry->execute([$this->cardid, $this->deskid, $this->stageid, $this->projectid,
                $this->executortype, $this->verifiertype, $this->executorid, $this->verifierid,
                $this->text, $this->status, $this->startdate, $this->exhours, $this->verhours]);
        }
        else
        {
            if ($this->verifiertype == 'none' && !empty($this->executorid))
            {
                $querry = $db->prepare('UPDATE task SET cardid=?, deskid=?, stageid=?, projectid=?, '
                        . 'executortype=?, verifiertype=?, executorid=?, '
                        . 'text=?, status=?, startdate=?, exhours=?, verhours=? '
                        . 'WHERE taskid=? LIMIT 1');
                $querry->execute([$this->cardid, $this->deskid, $this->stageid, $this->projectid,
                    $this->executortype, $this->verifiertype, $this->executorid,
                    $this->text, $this->status, $this->startdate, $this->exhours, $this->verhours,
                    $this->taskid]);
            }
            else if (!empty($this->verifierid) && !empty($this->executorid))
            {
                $querry = $db->prepare('UPDATE task SET cardid=?, deskid=?, stageid=?, projectid=?, '
                        . 'executortype=?, verifiertype=?, executorid=?, verifierid=?, '
                        . 'text=?, status=?, startdate=?, exhours=?, verhours=? '
                        . 'WHERE taskid=? LIMIT 1');
                $querry->execute([$this->cardid, $this->deskid, $this->stageid, $this->projectid,
                    $this->executortype, $this->verifiertype, $this->executorid, $this->verifierid,
                    $this->text, $this->status, $this->startdate, $this->exhours, $this->verhours,
                    $this->taskid]);
            }
            else
            {
                $querry = $db->prepare('UPDATE task SET cardid=?, deskid=?, stageid=?, projectid=?, '
                        . 'executortype=?, verifiertype=?, '
                        . 'text=?, status=?, startdate=?, exhours=?, verhours=? '
                        . 'WHERE taskid=? LIMIT 1');
                $querry->execute([$this->cardid, $this->deskid, $this->stageid, $this->projectid,
                    $this->executortype, $this->verifiertype,
                    $this->text, $this->status, $this->startdate, $this->exhours, $this->verhours,
                    $this->taskid]);
            }
        }
    }

    public function load($taskid)
    {
        $db = DataBase::get();
        $querry = $db->prepare('SELECT * FROM task WHERE taskid=? LIMIT 1');
        $querry->execute([$taskid]);
        $task = $querry->fetch();
        $this->assign($task);
    }

    public function assign($task)
    {
        $this->taskid = intval($task['taskid']);
        $this->cardid = intval($task['cardid']);
        $this->deskid = intval($task['deskid']);
        $this->stageid = intval($task['stageid']);
        $this->projectid = intval($task['projectid']);
        $this->executortype = $task['executortype'];
        $this->verifiertype = $task['verifiertype'];
        $this->executorid = intval($task['executorid']);
        $this->verifierid = intval($task['verifierid']);
        $this->text = $task['text'];
        $this->status = $task['status'];
        $this->startdate = $task['startdate'];
        $this->exhours = $task['exhours'];
        $this->verhours = $task['verhours'];
    }

    public function delete()
    {
        Dejavu::removeObject('Task', $this->taskid);

        $db = DataBase::get();
        $querry = $db->prepare('DELETE FROM task WHERE taskid=? LIMIT 1');
        $querry->execute([$this->taskid]);
    }

    public function activate()
    {
        if (empty($this->executorid))
            $this->findExecutor();
        if (empty($this->verifierid))
            $this->findVerifier();

        if (!empty($this->executorid) && (!empty($this->verifierid) || $this->verifiertype == 'none'))
        {
            $this->status = 'active';
            $this->startdate = date('Y-m-d H:m:s');
            $user = Dejavu::getObject('User', intval($this->executorid));
            $project = Dejavu::getObject('Project', intval($this->projectid));
            $text = mProject::getFullAddress($project);
            $days = $this->exhours / 24;
            sendmail($user->mail, "Таск",
                    "В проект $text поступила задача $this->text. "
                    . "Время на выполнение $days дня/дней."
                    . "Для получения деталей пройдите по <a href='http://". $_SERVER['SERVER_NAME']
                    ."/projects/$this->projectid/$this->stageid/$this->deskid/$this->cardid'>ссылке</a>."
            );
            return true;
        }
        else
        {
            return false;
        }
    }

    function findExecutor()
    {
        $project = Dejavu::getObject('Project', $this->projectid);
        $usersid = $project->usersid;

        for($i = 0; $i < count($usersid); $i++)
        {
            $user = Dejavu::getObject('User', $usersid[$i]);

            if ($user->type == $this->executortype)
            {
                $this->executorid = $usersid[$i];
                return;
            }
        }
        $this->executorid = self::findMostFreeUser($this->executortype);

        if (empty($this->executorid))
            return false;

        $project->addUser($this->executorid);
        $user = Dejavu::getObject('User', $this->executorid);
        $user->addProject($project->projectid);

        return true;
    }

    function findVerifier()
    {
        if ($this->verifiertype == 'none')
            return true;

        $project = Dejavu::getObject('Project', $this->projectid);
        $usersid = $project->usersid;

        for($i = 0; $i < count($usersid); $i++)
        {
            $user = Dejavu::getObject('User', $usersid[$i]);

            if ($user->type == $this->verifiertype)
            {
                $this->verifierid = $usersid[$i];
                return;
            }
        };
        $this->verifierid = self::findMostFreeUser($this->verifiertype);

        if (empty($this->verifierid))
            return false;
        //Go there!//
        $project->addUser($this->verifierid);
        $user = Dejavu::getObject('User', $this->verifierid);
        $user->addProject($project->projectid);
        return true;
    }

    static function findMostFreeUser($usertype)
    {
        $db = DataBase::get();
        $querry = $db->prepare('SELECT userid FROM user WHERE type=?');
        $querry->execute([$usertype]);
        $userList = $querry->fetchAll();
        if (empty($userList))
            return false;
        $stat = [];
        foreach ($userList as $user)
        {
            $user = Dejavu::getObject('User', intval($user['userid']));
            $stat[intval($user->userid)] = $user->projectsStat();
        }
        uasort($stat, function ($a, $b)
        {
            $bys_a = $a['all'] - $a['done'];
            $bys_b = $b['all'] - $b['done'];
            if ($bys_a == $bys_b)
            {
                $done_a = $a['done'];
                $done_b = $b['done'];
                if ($done_a == $done_b)
                    return 0;
                return ($done_a < $done_b) ? -1 : 1;
            }
            return ($bys_a < $bys_b) ? -1 : 1;
        });
        reset($stat);

        return key($stat);
    }



    public function start()
    {
        if ($this->status != 'active' && $this->status != 'rejected')
            return;

        $this->status = 'in_progress';
    }

    public function execute()
    {
        if ($this->status != 'in_progress')
            return;

        if ($this->verifiertype == 'none')
        {
            $this->status = 'verified';
            $project = Dejavu::getObject('Project', $this->projectid);
            $text = mProject::getFullAddress($project);
            $users = [];
            foreach($project->usersid as $userid)
            {
                $user = Dejavu::getObject('User', $userid);
                if ($userid == $this->executorid || $user->type == 'franchise' || $user->type == 'project_manager')
                {
                    sendmail($user->mail, "Таск",
                        "В проекте $text завршена задача $this->text. "
                        . "Для получения деталей пройдите по <a href='http://". $_SERVER['SERVER_NAME']
                        ."/projects/$this->projectid/$this->stageid/$this->deskid/$this->cardid'>ссылке</a>."
                    );
                }
            }
            $card = Dejavu::getObject("Card", $this->cardid);
            $card->finish($this);
        }
        else
        {
            $this->status = 'done';
            $user = Dejavu::getObject('User', intval($this->verifierid));
            $project = Dejavu::getObject('Project', intval($this->projectid));
            $text = mProject::getFullAddress($project);

            sendmail($user->mail, "Таск", "В проекте $text выполнена задача $this->text. "
                    . "Для проверки задачи перейдите по ссылке."
                    . "<a href='http://".$_SERVER['SERVER_NAME']."/projects/$this->projectid/$this->stageid/$this->deskid/$this->cardid/'>ссылке</a>");
        }
    }

    public function verify($good)
    {

        if ($this->status != 'done')
            return;
        if ($good)
        {
            $this->status = 'verified';
            $project = Dejavu::getObject('Project', intval($this->projectid));
            $text= mProject::getFullAddress($project);
            $users=[];
            foreach($project->usersid as $userid)
            {
                $user=Dejavu::getObject('User', intval($userid));
                        switch ($user->type){
                    case 'franchise':$users[]=$user; break;
                    case 'project_manager':$users[]=$user; break;
                        }
            }
            $user = Dejavu::getObject('User', intval($this->executorid));
            if($user->type!='franchise'&&$user->type!='project_manager')$users[]=$user;
            foreach($users as $user)
            {
            sendmail($user->mail, "Таск",
                    "В проекте $text подтверждена задача $this->text. "
                    . "Для получения деталей пройдите по <a href='http://". $_SERVER['SERVER_NAME']."/projects/$this->projectid/$this->stageid/$this->deskid/$this->cardid'>ссылке</a>."
                    );
            }
            $card = Dejavu::getObject("Card",$this->cardid);
            $card->finish($this);
        }
        else
        {
            $this->status = 'rejected';
            $user = Dejavu::getObject('User', intval($this->executorid));
            $project = Dejavu::getObject('Project', intval($this->projectid));
            $text= mProject::getFullAddress($project);
            sendmail($user->mail, "Таск",
                    "В проект $text откланена задача $this->text. "
                    . "Для получения деталей пройдите по <a href='http://". $_SERVER['SERVER_NAME']."/projects/$this->projectid/$this->stageid/$this->deskid/$this->cardid'>ссылке</a>."
                    );
        }
    }

    function isFinished()
    {
        return $this->status == 'verified';
    }

    function progress()
    {
        switch ($this->status)
        {
            case 'inactive': case 'active': case 'rejected':
                return 0;
            case 'done':
                return 0.5;
            case 'verified':
                return 1;
        }
    }

    function isAvail($usertype)
    {
        return (($this->verifiertype == $usertype && ($this->status == 'done' || $_SESSION['user']['type'] == 'franchise')) ||
                ($this->executortype == $usertype && (($this->status == 'rejected' || $this->status == 'active' || $this->status == 'in_progress') || $_SESSION['user']['type'] == 'franchise')));
    }

}
