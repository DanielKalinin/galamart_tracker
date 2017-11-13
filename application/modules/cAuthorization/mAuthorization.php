<?php

include_once MOD . '/User/User.php';

class mAuthorization
{

    //////////////////          Authorization form          ////////////////////
    static function catchAuthrizationForm()
    {
        if (empty($_POST['submitAuth']))
            return false;

        return true;
    }

    static function findUser()
    {
        $user = Dejavu::getObject('User', $_POST['pnum']);

        if ($user->isEmpty())
            return false;

        if (!$user->comparePassword($_POST['password']))
            return false;

        return $user;
    }

    static function startSession($user)
    {
        session_start();
        $_SESSION['user']['userid'] = $user->userid;
        $_SESSION['user']['fname'] = $user->fname;
        $_SESSION['user']['sname'] = $user->sname;
        $_SESSION['user']['type'] = $user->type;
    }

    static function redirect()
    {
        session_start();
        if ($_SESSION['URI'] == '' || $_SESSION['URI'] == '/authorization')
            header('Location: /projects');
        else
            header('Location: ' . $_SESSION['URI']);
    }

    ////////////////////////////////////////////////////////////////////////////
    //////////////////////////////      deauth     /////////////////////////////
    static function endSession()
    {
        session_start();
        session_unset();
        session_destroy();
        session_commit();
    }

    ////////////////////////////////////////////////////////////////////////////
    //////////////////////        Password repair form        //////////////////
    static $validPnum = true;

    static function catchRepairForm()
    {
        if (empty($_POST['submitRepair']))
            return false;

        return true;
    }

    static function findUserForRepair()
    {
        $user = Dejavu::getObject('User', $_POST['pnum']);

        if ($user->isEmpty())
            return self::$validPnum = false;

        return $user;
    }

    static function makeRepairHash($user)
    {
        return $user->prepareChangePassword();
    }

    static function sendRepairMail($user, $hash)
    {
        sendmail($user->mail, "Восстановление пароля", "Ссылка для восстановления пароля.<br>"
                . "<a href='http://{$_SERVER['SERVER_NAME']}/authorization/repair/$hash'>"
                . "http://{$_SERVER['SERVER_NAME']}/authorization/repair/$hash</a>");
    }

    ////////////////////////////////////////////////////////////////////////////
    //////////////////////       Confirm repair form      //////////////////////
    static $validConfirm = ['password' => true, 'passwordRepeated' => true];

    static function checkHash($hash)
    {
        $user = Dejavu::getObject('User', $hash);
        if ($user->isEmpty())
            return false;
        return $user;
    }

    static function catchConfirmForm()
    {
        if (empty($_POST['submitConfirm']))
            return false;

        $success = true;
        if (!isPassword($_POST['password']))
            $success = self::$validConfirm['password'] = false;
        if ($_POST['password'] != $_POST['passwordRepeated'])
            $success = self::$validConfirm['passwordRepeated'] = false;

        return $success;
    }

    static function repairPassword($hash)
    {
        $user = Dejavu::getObject('User', $hash);
        $user->changePassword($_POST['password']);
        $user->finishChangePassword();
    }

    ////////////////////////////////////////////////////////////////////////////
}
