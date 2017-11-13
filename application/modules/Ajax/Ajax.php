<?php

include_once MOD . '/Project/Card.php';
include_once MOD . '/Form/Form.php';
include_once MOD . '/File/File.php';
include_once MOD . '/User/User.php';
include_once MOD . '/Project/CardLogger.php';
include_once MOD . '/Chat/Message.php';

class Ajax
{
    function save($cardid)
    {
        $resp = '';
        $maxFileSize = 5 * 1024 * 1024;
        $card = Dejavu::getObject('Card', intval($cardid));
        $form = Dejavu::getObject('Form', $card->formid);
        foreach ($form->inputsid as $inputid)
        {
            $input = Dejavu::getObject('Input', $inputid);
            $name = $input->name;
            if (empty($_FILES[$name]))
            {
                if (isset($_POST[$name]))
                    $input->setVal($_POST[$name]);
            }
            else
            {
                $files = $_FILES[$name];
                $size = count($files['name']);
                $inputVal = [];
                for ($i = 0; $i < $size; $i++)
                {
                    if ($files['error'][$i] == 4)
                        continue;
                    $fileD = [
                        'error' => $files['error'][$i],
                        'size' => $files['size'][$i],
                        'name' => $files['name'][$i],
                        'tmp_name' => $files['tmp_name'][$i],
                        'type' => $files['type'][$i]
                        ];
                    if ($fileD['error'] || $fileD['size'] > $maxFileSize)
                        continue;
                    $fileD['data'] = file_get_contents($fileD['tmp_name']);
                    $fileName = $fileD['name'];
                    $file = new File($fileD);
                    $file->save();
                    $resp .= '<a href="/download/' . $file->fileid . '">' .  $fileName . "</a> ";
                    $inputVal[] = ['href' => '/download/' . $file->fileid, 'fileName' => $fileName];
                }
                $input->setVal($inputVal);
            }
        }
        echo $resp;
    }

    function saveUser($userid)
    {
        $user = Dejavu::getObject('User',intval($userid));
        if (!empty($_POST['fname']))
            $user->fname = $_POST['fname'];
        if (!empty($_POST['sname']))
            $user->sname = $_POST['sname'];
        if (!empty($_POST['tname']))
            $user->tname = $_POST['tname'];

    }
    
    function saveFormUpd($cardid) 
    {
        if ($_POST["form_upd"] == 1)
            CardLogger::log(intval($cardid), 'form_upd');
    }
    
    function checkMsg($messageid) 
    {
        $message = Dejavu::getObject('Message', intval($messageid));
        $message->check();
    }
}
