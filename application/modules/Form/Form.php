<?php

include_once MOD . '/Form/Input.php';

class Form
{
    var $formid;
    var $inputsid = [];

    function __construct($initData)
    {
        if (is_int($initData))
            $this->loadId($initData);
        else if (is_array($initData))
            $this->loadArray($initData);
        else
            throw Dejavu::getObject('Exception', 'Bad initialization data in Form constructor');
    }

    function loadId($formid)
    {
        $db = DataBase::get();
        $querry = $db->prepare("SELECT * FROM form WHERE formid=? LIMIT 1");
        $querry->execute([$formid]);
        $form = $querry->fetch();
        $this->assign($form);
    }

    function loadArray($array)
    {
        if (isset($array['formid']))
            $this->assign($array);
        else
            $this->create($array);
    }

    function assign($form)
    {
        $this->formid = intval($form['formid']);

        if (is_string($form['inputsid']))
            $this->inputsid = explode_to_int(' ', $form['inputsid']);
        else
            $this->inputsid = $form['inputsid'];
    }

    function create($data)
    {
        $this->saveEmpty();
        $this->formid = $this->getLastId();
        foreach ($data as $input_array)
        {
            $input_array['formid'] = $this->formid;
            $input = Dejavu::getObject('Input', $input_array);
            $this->inputsid[] = $input->inputid;
        }
    }

    function delete()
    {
        foreach($this->inputsid as $inputid)
        {
            $input = Dejavu::getObject('Input', $inputid);
            $input->delete();
        }

        Dejavu::removeObject('Form', $this->formid);

        $db = DataBase::get();
        $querry = $db->prepare('DELETE FROM form WHERE formid=? LIMIT 1');
        $querry->execute([$this->formid]);
    }

    private function saveEmpty()
    {
        $db = DataBase::get();
        $querry = $db->prepare('INSERT INTO form (inputsid) VALUES ("")');
        $querry->execute([]);
    }

    private function getLastId()
    {
        $db = DataBase::get();
        return intval($db->lastInsertId());
    }

    function getHtml()
    {
        $res = '<form class="auto_form" method="post" enctype="multipart/form-data">';
        foreach ($this->inputsid as $inputid)
        {
            $res .= '<div class="form-group">';
            $input = Dejavu::getObject('Input', $inputid);

           // <label class="btn btn-default btn-file">
          //      Browse <input type="file" style="display: none;">
          //  </label>

            $res .= '<label for="'.$input->name.'">' . $input->text . '</label>';
            if ($input->type == 'file')
            {
                $res .= '<label class="btn btn-default btn-file" style="display: block">';
                $res .= 'Выбрать файл ';
                $res .= $input->getHtml();
                $res .= '</label>';
            }
            else
            {
                $res .= $input->getHtml();
            }

            $res .= '</div>';
        }
        //$res .= '<input type="submit" name="submitForm" value="Сохранить"></input>';
        $res .= '</form>';
        return $res;
    }

    function save()
    {
        $inputsid = implode_to_str(' ', $this->inputsid);

        $db = DataBase::get();
        $querry = $db->prepare('UPDATE form SET inputsid=? WHERE formid=? LIMIT 1');
        $querry->execute([$inputsid, $this->formid]);
    }

    function isEmpty()
    {
        return empty($this->inputsid);
    }
}
