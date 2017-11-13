<?php

class Input
{
    var $inputid;
    var $type;
    var $name;
    var $text;
    var $formid;
    var $options = [];
    var $deleted = false;

    function __construct($initData)
    {
        if (is_int($initData))
            $this->loadId($initData);
        else if (is_array($initData))
            $this->loadArray($initData);
        else
            throw Dejavu::getObject('Exception', "Bad initialization data in Input constructor");
    }

    function loadId($inputid)
    {
        $db = DataBase::get();
        $querry = $db->prepare('SELECT * FROM input WHERE inputid=? LIMIT 1');
        $querry->execute([$inputid]);

        $input = $querry->fetch();

        $this->inputid = intval($input['inputid']);
        $this->type = $input['type'];
        $this->name = $input['name'];
        $this->text = $input['text'];
        $this->formid = $input['formid'];
        $input['options'] = trim($input['options']);
        if(!empty($input['type']))
        {
           $func = $input['type'].'Load';
           $this->$func($input);
        }
    }

    function loadArray($array)
    {
        if (isset($array['inputid']))
            $this->assign($array);
        else
            $this->create($array);
    }

    function assign($input)
    {
        $this->inputid = $input['inputid'];
        $this->type = $input['type'];
        $this->name = $input['name'];
        $this->text = $input['text'];
        $this->formid = $input['formid'];
        $this->options = $input['options'];
    }

    function create($input)
    {
        $this->saveEmpty();
        $this->inputid = $this->getLastId();

        $this->type = $input['type'];
        $this->name = $input['name'];
        $this->text = $input['text'];
        $this->formid = $input['formid'];
        $this->options = $input['options'];
    }

    function delete()
    {
        Dejavu::removeObject('Input', $this->inputid);

        $db = DataBase::get();
        $querry = $db->prepare('DELETE FROM input WHERE inputid=? LIMIT 1');
        $querry->execute([$this->inputid]);
    }

    private function saveEmpty()
    {
        $db = DataBase::get();
        $querry = $db->prepare('INSERT INTO input (type, name, text, formid, options) VALUES ("", "", "", 0, "")');
        $querry->execute([]);
    }

    private function getLastId()
    {
        $db = DataBase::get();
        return intval($db->lastInsertId());
    }

    function save()
    {
        $func = $this->type.'Save';
        $options = $this->$func();
        if (empty($options))
            $options = "";

        $db = DataBase::get();
        $querry = $db->prepare('UPDATE input SET type=?, name=?, text=?, formid=?, options=? WHERE inputid=? LIMIT 1');
        $querry->execute([$this->type, $this->name, $this->text, $this->formid, $options, $this->inputid]);
    }

    function submitSave()
    {
        return $this->options['value'];
    }

    function textSave()
    {
        return  str_replace('>', '&gt;', str_replace('<', '&lt;',$this->options['text']));
    }

    function fileSave()
    {
        $res = "";
        if (!empty($this->options))
        {
            foreach($this->options as $option)
            $res .= $option['href'].'~~~'.$option['fileName'].'###';
        }
        return $res;
    }

    function checkboxSave()
    {
        $res = "";
        if (!empty($this->options))
        {
            foreach($this->options as $option) {
                if ($option['selected'])
                    $res .= '!';
                $res .= $option['checkboxValue'].'-'.implode('_', explode(' ', $option['checkboxName'])).' ';
            }
        }
        return $res;
    }

    function radioSave()
    {
        $res = "";
        if (!empty($this->options))
        {
            foreach($this->options as $option) {
                if ($option['selected'])
                    $res .= '!';
                $res .= $option['radioValue'].'-'.implode('_', explode(' ', $option['radioName'])).' ';
            }
        }
        return $res;
    }

    function selectSave()
    {
        $res = "";
        if (!empty($this->options))
        {
            foreach($this->options as $option) {
                if ($option['selected'])
                    $res .= '!';
                $res .= $option['optionValue'].'-'.implode('_', explode(' ', $option['optionName'])).' ';
            }
        }
        return $res;
    }

    function submitLoad($input)
    {
        $this->options = ['value' => $input['options']];
    }

    function textLoad($input)
    {
        $this->options = ['text' => $input['options']];
    }

    function fileLoad($input)
    {
        $options = explode('###', $input['options']);
        foreach($options as $option)
        {
            $split = explode('~~~', $option);
            $this->options[] = ['href' => $split[0], 'fileName' => $split[1]];
        }
    }

    function checkboxLoad($input)
    {
        $options = explode(' ', $input['options']);
        foreach($options as $option)
        {
            $split = explode('-', $option);
            $name = implode(' ', explode('_', $split[1]));

            if ($split[0][0] == '!')
                $this->options[] = ['checkboxValue' => substr($split[0], 1), 'checkboxName' => $name, 'selected' => true];
            else
                $this->options[] = ['checkboxValue' => $split[0], 'checkboxName' => $name, 'selected' => false];
        }
    }

    function radioLoad($input)
    {
        $options = explode(' ', $input['options']);
        foreach($options as $option)
        {
            $split = explode('-', $option);
            $name = implode(' ', explode('_', $split[1]));

            if ($split[0][0] == '!')
                $this->options[] = ['radioValue' => substr($split[0], 1), 'radioName' => $name, 'selected' => true];
            else
                $this->options[] = ['radioValue' => $split[0], 'radioName' => $name, 'selected' => false];
        }
    }

    function selectLoad($input)
    {
        $options = explode(' ', $input['options']);
        foreach($options as $option)
        {
            $split = explode('-', $option);
            $name = implode(' ', explode('_', $split[1]));

            if ($split[0][0] == '!')
                $this->options[] = ['optionValue' => substr($split[0], 1), 'optionName' => $name, 'selected' => true];
            else
                $this->options[] = ['optionValue' => $split[0], 'optionName' => $name, 'selected' => false];
        }
    }

    function getHtml()
    {
        $func = $this->type.'Html';
        return $this->$func();
    }

    function submitHtml()
    {
        $res = '<input class="form-control" type="submit" name="'.$this->name.'" value="'.$this->options['value'].'"></input>';
        return $res;
    }

    function textHtml()
    {
        $res = '<input class="form-control" type="text" name="'.$this->name.'" ';
        $res .= 'value="'.$this->options['text'].'"></input>';
        return $res;
    }

    function fileHtml()
    {
        $res = '';
        $res .= '<input type="file" name="'.$this->name.'[]" style="display: none;" multiple/>';
        $res .= '<p class="help-block">';
        foreach ($this->options as $option)
        {
            $dis = 'inline';
            if (empty($option['href']))
                $dis = 'none';
            $res .= '<a href="'.$option['href'].'">'.$option['fileName'].'</a> ';
        }
        $res .= '</p>';
        return $res;
    }

    function checkboxHtml()
    {
        $res = '';
        foreach ($this->options as $option)
        {
            $res .= '<input class="form-control" type="checkbox" name="'.$this->name.'[]" value="'.$option['checkboxValue'].'"';
            if ($option['selected'])
                $res .= ' checked';
            $res .= '>'.$option['checkboxName'].'</input><br>';
        }
        return $res;
    }

    function radioHtml()
    {
        $res = '';
        foreach ($this->options as $option)
        {
            $res .= '<input class="form-control" type="radio" name="'.$this->name.'" value="'.$option['radioValue'].'"';
            if ($option['selected'])
                $res .= ' checked';
            $res .= '>'.$option['radioName'].'</input><br>';
        }
        return $res;
    }

    function selectHtml()
    {
        $res = '<select class="form-control" name="'.$this->name.'">';
        foreach($this->options as $option)
        {
            $res .= '<option value="'.$option['optionValue'].'"';
            if ($option['selected'])
                $res .= ' selected';
            $res .= '>'.$option['optionName'].'</option>';
        }
        $res .= '</select>';
        return $res;
    }

    function setVal($val)
    {
        $func = $this->type.'Set';
        return $this->$func($val);
    }

    function submitSet($val)
    {
        $this->options['value'] = $val;
    }

    function textSet($val)
    {
        $this->options['text'] = $val;
    }

    function fileSet($val)
    {
        if (!empty($val))
            $this->options = $val;
    }

    function checkboxSet($val)
    {
        if (!empty($this->options))
        {
            foreach ($this->options as &$option)
            {
                $option['selected'] = false;
            }
        if (empty($val))
            return;
            foreach($this->options as &$option)
            {
                foreach($val as $value)
                {
                    if ($option['checkboxValue'] == $value)
                        $option['selected'] = true;
                }
            }
        }
    }

    function radioSet($val)
    {
        if (!empty($this->options))
        {
            foreach ($this->options as &$option)
            {
                $option['selected'] = false;
            }
            foreach($this->options as &$option)
            {
                if ($option['radioValue'] == $val)
                    $option['selected'] = true;
            }
        }
    }

    function selectSet($val)
    {
        if (!empty($this->options))
        {
            foreach ($this->options as &$option)
            {
                $option['selected'] = false;
            }
            foreach($this->options as &$option)
            {
                if ($option['radioValue'] == $val)
                    $option['selected'] = true;
            }
        }
    }

}
