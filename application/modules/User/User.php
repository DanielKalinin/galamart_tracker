<?php

class User
{

    var $userid;
    var $fname;
    var $sname;
    var $tname;
    var $mail;
    var $pnum;
    private $password;
    var $type;
    var $projectsid = [];
    var $avatarid;

    function __construct($initData)
    {
        if (is_int($initData))
        {
            $this->loadId($initData);
        }
        else if (is_string($initData))
        {
            if (isMail($initData))
                $this->loadMail($initData);
            else if (isPhoneNumber($initData))
                $this->loadPnum($initData);
            else
            {
                $this->loadHash($initData);
            }
        }
        else
        {
            $this->assign($initData);
        }
    }


    function save()
    {
        $db = DataBase::get();
        $projectsid = implode_to_str(' ', $this->projectsid);
        if (empty($this->userid))
        {
            $querry = $db->prepare('INSERT INTO user (fname, sname, tname, mail, pnum, password, type, avatarid, projectsid) '
                    . 'VALUES (?, ?, ?, ?, ?, ?, ?, ?,?)');
            $querry->execute([$this->fname, $this->sname, $this->tname, $this->mail,
                $this->pnum, $this->password, $this->type,$this->avatarid, $projectsid]);
            //avatar
            $querry=$db->prepate('INSERT INTO avatar VALUE ("") ');
            $querry->execute();
        }
        else
        {
            //kostil
            $querry = $db->prepare('UPDATE user SET fname=?, sname=?, tname=?, mail=?, pnum=?, password=?, '
                    . 'projectsid=?, avatarid=? '
                    . 'WHERE userid=? LIMIT 1');
            $querry->execute([$this->fname, $this->sname, $this->tname, $this->mail,
                $this->pnum, $this->password, $projectsid,$this->avatarid, $this->userid]);
        }
    }

    function assign($user)
    {
        $this->userid = intval($user['userid']);
        $this->fname = $user['fname'];
        $this->sname = $user['sname'];
        $this->tname = $user['tname'];
        $this->mail = $user['mail'];
        $this->pnum = $user['pnum'];
        $this->password = $user['password'];
        $this->type = $user['type'];
        $this->projectsid = $user['projectsid'];
        $this->avatarid=$user['avatarid'];
    }

    function loadId($userid)
    {
        $db = DataBase::get();
        $querry = $db->prepare('SELECT * FROM user WHERE userid=? LIMIT 1');
        $querry->execute([$userid]);
        $user = $querry->fetch();
        $user['projectsid'] = explode_to_int(' ', $user['projectsid']);
        $this->assign($user);
    }

    function loadMail($mail)
    {
        $db = DataBase::get();
        $querry = $db->prepare('SELECT * FROM user WHERE mail=? LIMIT 1');
        $querry->execute([$mail]);
        $user = $querry->fetch();
        $user['projectsid'] = explode_to_int(' ', $user['projectsid']);
        $this->assign($user);
    }

    function loadPnum($pnum)
    {
        $db = DataBase::get();
        $querry = $db->prepare('SELECT * FROM user WHERE pnum=? LIMIT 1');
        $querry->execute([$pnum]);
        $user = $querry->fetch();
        $user['projectsid'] = explode_to_int(' ', $user['projectsid']);
        $this->assign($user);
    }

    function loadHash($hash)
    {
        $db = DataBase::get();
        $querry = $db->prepare('SELECT userid FROM reset WHERE hash=? LIMIT 1');
        $querry->execute([$hash]);
        $userid = intval($querry->fetch()['userid']);
        if (empty($userid))
            return;
        $this->loadId($userid);
    }

    function delete()
    {
        $db = DataBase::get();
        $querry = $db->prepare('DELETE FROM user WHERE userid=? LIMIT 1');
        $querry->execute([$this->userid]);
    }

    function comparePassword($password)
    {
        return password_verify($password, $this->password);
    }

    function prepareChangePassword()
    {
        $hash = password_hash($this->mail . $this->pnum . $this->password . random_int(0, 10000), PASSWORD_BCRYPT);
        $db = DataBase::get();
        $querry = $db->prepare("INSERT INTO reset (userid, hash) VALUES (?, ?)");
        $querry->execute([$this->userid, $hash]);
        return $hash;
    }

    function changePassword($newPassword)
    {
        $this->password = password_hash($newPassword, PASSWORD_BCRYPT);
    }

    function finishChangePassword()
    {
        $db = DataBase::get();
        $querry = $db->prepare("DELETE FROM reset WHERE userid=?");
        $querry->execute([$this->userid]);
    }

    function isEmpty()
    {
        return empty($this->fname);
    }

    function getArray()
    {
        return [
            'userid' => $this->userid,
            'fname' => $this->fname,
            'sname' => $this->sname,
            'tname' => $this->tname,
            'mail' => $this->mail,
            'pnum' => $this->pnum,
            'password' => $this->password,
            'type' => $this->type,
            'avatarid'=>$this->avatarid,
            'projectsid' => $this->projectsid
        ];
    }

    function addProject($_projectid)
    {
        foreach ($this->projectsid as $projectid)
        {
            if ($projectid == $_projectid)
                return;
        }

        $this->projectsid[] = $_projectid;
    }

    function createAvatar()
    {
        $db = DataBase::get();
        $querry = $db->prepare("INSERT INTO avatar VALUES ()");
        $querry->execute([]);
        $avatarid = (int)$db->lastInsertId();

        $this->avatarid = $avatarid;
    }

    function projectsStat()
    {
        $stat = [
            'done' => 0,
            'all' => count($this->projectsid)
        ];
        foreach ($this->projectsid as $projectid)
        {
            $project = Dejavu::getObject('Project', $projectid);
            if ($project->isFinished())
            {
                $stat['done']++;
            }
        }
        return $stat;
    }

    function getProject($index)
    {
        return Dejavu::getObject('Project', $this->projectsid[$index]);
    }

    function getAwaitingCards($projectid) // for non key roles
    {
        $db = DataBase::get();
        $querry = $db->prepare("SELECT card.cardid, task.status as status, card.name, task.stageid, task.deskid, card.reactivated "
                . "FROM task, card "
                . "WHERE task.projectid=:projectid AND "
                . "(task.executorid=:userid AND task.status IN ('rejected', 'active', 'in_progress') "
                . "OR (task.verifierid=:userid AND task.status='done')) AND "
                . "card.cardid=task.cardid");
        $querry->execute([':userid' => $this->userid, ':projectid' => $projectid]);
        $cards = [];
        while (!empty($card = $querry->fetch()))
        {
            $cards[] = $card;
        }
        return $cards;
    }

    function isThereWorkToDo($projectid)
    {
        $db = DataBase::get();
        $querry = $db->prepare("SELECT taskid "
                . "FROM task "
                . "WHERE projectid=:projectid AND "
                . "(task.executorid=:userid AND task.status IN ('rejected', 'active', 'in_progress') "
                . "OR (task.verifierid=:userid AND task.status='done')) LIMIT 1");
        $querry->execute([':userid' => $this->userid, ':projectid' => $projectid]);
        return !empty($querry->fetch()) || $this->type == 'franchise' || $this->type == 'project_manager' || $this->type == 'development_manager';
    }

}
