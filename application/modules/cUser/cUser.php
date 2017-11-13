<?php

include_once 'mUser.php';
include_once 'vUser.php';

class cUser
{

    static function profile($userid)
    {
        $userid=(int)$userid;
        $user = Dejavu::getObject('User', $userid);
        if (empty($user->mail))
            http_response_code(404);
        else
        {
            if(mUser::catchSaveAvatar())
                self::saveAvatar($user->avatarid);
            if(mUser::catchChange())
                mUser::change($user->userid);
            mUser::start();
            vUser::makeProfile(mUser::getProfileInfo($userid));
        }
    }

       static function avatar($avatarid)
    {
        $avatarid=(int)$avatarid;
         header('Content-Type: image/jpeg');
         header('Content-Transfer-Encoding: base64');
         $file=mUser::getAvatarImg($avatarid);
         echo $file;

    }
      static function saveAvatar($avatarid){
          $avatarid=(int)$avatarid;
          mUser::saveAvatar($avatarid);
      }

      static function changePassword($userid){
            mUser::changePassword($userid);
      }

}
