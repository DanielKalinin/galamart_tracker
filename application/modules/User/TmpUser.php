<?php

class TmpUser
{

    var $code;
    var $fname;
    var $sname;
    var $tname;
    var $mail;
    var $pnum;
    private $password;
    var $type;

    function __construct($initData)
    {
        if (is_array($initData))
        {
            $initData['password'] = password_hash($initData['password'], PASSWORD_BCRYPT);
            $this->assign($initData);
        }
        else
        {
            $this->load($initData);
        }
    }

    function isUnique()
    {
        $db = DataBase::get();
        $querry = $db->prepare('SELECT mail, pnum FROM user WHERE mail=:mail OR pnum=:pnum '
                . 'UNION SELECT mail, pnum FROM user_tmp WHERE mail=:mail OR pnum=:pnum');
        $querry->execute([':mail' => $this->mail, ':pnum' => $this->pnum]);
        $matches = $querry->fetch();
        return empty($matches);
    }

    function isEmpty()
    {
        return empty($this->fname);
    }

    function save()
    {
        $this->makeCode();
        $db = DataBase::get();
        $querry = $db->prepare('INSERT INTO user_tmp (code, fname, sname, tname, mail, pnum, password, type) '
                . 'VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $querry->execute([$this->code, $this->fname, $this->sname, $this->tname, $this->mail,
            $this->pnum, $this->password, $this->type]);
        return $this->code;
    }

    function makeCode()
    {
        $db = DataBase::get();
        $querry = $db->prepare('SELECT code FROM user_tmp WHERE code=?');
        do
        {
            $this->code = random_int(1000, 9999);
            $querry->execute([$this->code]);
        } while (!empty($querry->fetch()));
    }

    function assign($user)
    {
        $this->code = $user['code'];
        $this->fname = $user['fname'];
        $this->sname = $user['sname'];
        $this->tname = $user['tname'];
        $this->mail = $user['mail'];
        $this->pnum = $user['pnum'];
        $this->password = $user['password'];
        $this->type = $user['type'];
    }

    function getArray()
    {
        $user = [];
        $user['code'] = $this->code;
        $user['fname'] = $this->fname;
        $user['sname'] = $this->sname;
        $user['tname'] = $this->tname;
        $user['mail'] = $this->mail;
        $user['pnum'] = $this->pnum;
        $user['password'] = $this->password;
        $user['type'] = $this->type;
        return $user;
    }

    function load($code)
    {
        $db = DataBase::get();
        $querry = $db->prepare('SELECT * FROM user_tmp WHERE code=? LIMIT 1');
        $querry->execute([$code]);
        $user = $querry->fetch();
        $this->assign($user);
    }

    function delete()
    {
        $db = DataBase::get();
        $querry = $db->prepare('DELETE FROM user_tmp WHERE code=? LIMIT 1');
        $querry->execute([$this->code]);
    }

}
