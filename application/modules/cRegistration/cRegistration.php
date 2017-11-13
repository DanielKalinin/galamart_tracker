<?php

include_once 'mRegistration.php';
include_once 'vRegistration.php';

class cRegistration
{

    static function index()
    {
        $success = mRegistration::catchRegistrationForm();

        if ($success && ($code = mRegistration::addTmpUser()))
        {
            mRegistration::sendRegCookie($code);
            mRegistration::sendRegMail($code);
            header("Location: /registration/confirm");
        }
        else
        {
            vRegistration::registrationForm(mRegistration::$validRegInputs, mRegistration::hasRegCookie());
        }
    }

    static function confirm()
    {
        $success = mRegistration::catchRegCookie();
        if ($success)
        {
            $success = mRegistration::catchConfirmForm();
            if ($success && mRegistration::addUser())
            {
                mRegistration::deleteRegCookie();
                header("Location: /authorization");
            }
            else
            {
                vRegistration::confirmForm();
            }
        }
        else
        {
            header("Location: /registration");
        }
    }

    static function repeat()
    {
        if (mRegistration::catchRegCookie())
            mRegistration::sendRegMail();
        else
            http_response_code(404);
    }

}
