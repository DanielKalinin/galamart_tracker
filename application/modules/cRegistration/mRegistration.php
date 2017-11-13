<?php

class mRegistration
{

    ///////////////////////      Registration from     /////////////////////////
    static $validRegInputs = [
        'fname' => true,
        'sname' => true,
        'tname' => true,
        'mail' => true,
        'pnum' => true,
        'password' => true,
        'passwordRepeated' => true,
        'unique' => true,
    ];

    static function catchRegistrationForm()
    {
        if (empty($_POST['submitReg']))
            return false;

        $success = true;
        if (!isName($_POST['fname']))
            $success = self::$validRegInputs['fname'] = false;
        if (!isName($_POST['sname']))
            $success = self::$validRegInputs['sname'] = false;
        if (!isName($_POST['tname']))
            $success = self::$validRegInputs['tname'] = false;

        if (!isMail($_POST['mail']))
            $success = self::$validRegInputs['mail'] = false;

        if (!isPhoneNumber($_POST['pnum']))
            $success = self::$validRegInputs['pnum'] = false;

        if (!isPassword($_POST['password']))
            $success = self::$validRegInputs['password'] = false;
        if ($_POST['password'] != $_POST['passwordRepeated'])
            $success = self::$validRegInputs['passwordRepeated'] = false;

        return $success;
    }

    static function addTmpUser()
    {
        include_once MOD . '/User/TmpUser.php';
        $tmpUser = new TmpUser($_POST);
        if ($tmpUser->isUnique())
        {
            return $tmpUser->save();
        }
        else
        {
            self::$validRegInputs['unique'] = false;
            return false;
        }
    }

    static function sendRegMail($code)
    {
        sendmail($_POST['mail'], "Регистрация", "Код подтверждения регистрации $code.");
    }

    static function sendRegCookie($code)
    {
        session_start();
        $_SESSION['regAwaitingCode'] = true;
        $_SESSION['mail'] = $_POST['mail'];
        $_SESSION['code'] = $code;
    }

    static function hasRegCookie()
    {
        session_start();
        return $_SESSION['regAwaitingCode'];
    }

    ////////////////////////////////////////////////////////////////////////////
    ///////////////////////      Confirm form      /////////////////////////////
    static function catchRegCookie()
    {
        session_start();
        return $_SESSION['regAwaitingCode'];
    }

    static function catchConfirmForm()
    {
        if (empty($_POST['submitConf']))
            return false;
        else
            return true;
    }

    static function addUser()
    {
        include_once MOD . '/User/TmpUser.php';
        include_once MOD . '/User/User.php';

        $tmpUser = Dejavu::getObject('TmpUser', $_POST['code']);

        if ($tmpUser->isEmpty())
        {
            return false;
        }
        else
        {
            $user = Dejavu::getObject('User', $tmpUser->getArray());
			$user->createAvatar();

            if (!empty($user))
                $tmpUser->delete();

            return true;
        }
    }

    static function deleteRegCookie()
    {
        session_start();
        session_unset();
        session_destroy();
        session_commit();
    }

    ////////////////////////////////////////////////////////////////////////////
}
