<?php

include_once 'mAuthorization.php';
include_once 'vAuthorization.php';

class cAuthorization
{

    static function index()
    {
        $success = mAuthorization::catchAuthrizationForm();

        if ($success)
        {
            $user = mAuthorization::findUser();

            if ($user)
            {
                mAuthorization::redirect();
                authorized();
                mAuthorization::startSession($user);
            }
            else
            {
                vAuthorization::authorizationForm(false);
            }
        }
        else
        {
            vAuthorization::authorizationForm(true);
        }
    }

    static function repair()
    {
        $success = mAuthorization::catchRepairForm();
        if ($success && ($user = mAuthorization::findUserForRepair()))
        {
            $hash = mAuthorization::makeRepairHash($user);
            mAuthorization::sendRepairMail($user, $hash);
            vAuthorization::successRepair();
        }
        else
        {
            vAuthorization::repairForm(mAuthorization::$validPnum);
        }
    }

    static function confirmHash($hash)
    {
        $success = mAuthorization::checkHash($hash);
        if ($success)
        {
            $success = mAuthorization::catchConfirmForm();
            if ($success)
            {
                mAuthorization::repairPassword($hash);
                header("Location: /authorization");
            }
            else
            {
                vAuthorization::confirmForm(mAuthorization::$validConfirm);
            }
        }
        else
        {
            http_response_code(404);
        }
    }

    static function deauth()
    {
        mAuthorization::endSession();
        header("Location: /");
    }

}
