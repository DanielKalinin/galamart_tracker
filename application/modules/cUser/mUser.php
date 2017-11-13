<?php

include_once MOD . '/User/User.php';
include_once APP . '/regex.php';

class mUser
{

    static function start()
    {
        session_start();
    }

     static function catchSaveAvatar()
    {
        if (empty($_POST['avatarSave']))
            return false;
        else
            return true;
    }

    static function catchChange()
    {
        return !empty($_POST['submitProfile']);
    }

    static function getProfileInfo($userid)
    {
        $user = Dejavu::getObject('User', $userid);
        return $user->getArray();
    }

    public static function getAvatarImg($avatarid)
    {
        $db= DataBase::get();
        $query=$db->prepare("SELECT * FROM avatar WHERE avatarid=? LIMIT 1");
        $query->execute([$avatarid]);
        $mas=$query->fetch();
        $image = $mas['image'];
        return  $image;
    }

    public static function saveAvatar($avatarid)
    {
        $db= DataBase::get();
        $query=$db->prepare("UPDATE avatar SET image=? WHERE avatarid=?");
        $image = file_get_contents($_FILES['avatar']['tmp_name']);
        $query->execute([$image,$avatarid]);
    }

    public static function change($userid)
    {
        $user = Dejavu::getObject("User", (int)$userid);
        
        $return = true;
        
        $checked = [
            'fname' => true,
            'sname' => true,
            'tname' => true,
            
            'mail' => true,
            
            'pnum' => true,
            
            'changedPassword' => true
            ];
        
        
        if (!isName($_POST['fname']))
            $checked['fname'] = false;
        else
            $user->fname = $_POST['fname'];
        if (!isName($_POST['sname']))
            $checked['sname'] = false;
        else
            $user->sname = $_POST['sname'];
        if (!isName($_POST['tname']))
            $checked['tname'] = false;
        else
            $user->tname = $_POST['tname'];
        
        if (isMail($_POST['mail']))
            $checked['mail'] = false;
        else
            $user->mail = $_POST['mail'];
        
        if (isPhoneNumber($_POST['pnum']))
            $checked['pnum'] = false;
        else
            $user->pnum = $_POST['pnum'];
        
        if (!isPassword($_POST['changedPassword']) || $_POST['confirmedPassword'] != $_POST['changedPassword'])
            $checked['changedPassword'] = false;
        else
            $user->changePassword($_POST['changedPassword']);
    }
}
