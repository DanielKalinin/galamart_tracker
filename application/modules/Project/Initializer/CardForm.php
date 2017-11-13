<?php

class CardForm
{

    var $inputs = [];

    function __construct($inputs)
    {
        $this->inputs = $inputs;
    }

    function makeHTML()
    {
        $out = '<form enctype="multipart/form-data" method="post"><table>';
        foreach ($this->inputs as $input)
        {
            $out .= '<tr>';
            $out .= '<td>';
            $out .= $input['text'] . ':';
            $out .= '</td>';
            switch ($input['type'])
            {
                case 'text':
                    $out .= '<td><input type="text" name="' . $input['name'] . '" value=""></input></td>';
                    break;
                case 'file':
                    $out .= '<td><a name="'.$input['name'].'" href=""></a><input type="file" name="' . $input['name'] . '" value=""></input></td>';
                    break;
                case 'option':
                    $out .= '<td><select name="' . $input['name'] . '">';
                    $out .= '<option selected>Ничего</option>';
                    foreach ($input['values'] as $key => $value)
                    {
                        $out .= '<option value="' . $value . '">'.$key.'</option>';
                    }
                    $out .= '</select></td>';
                    break;
                case 'radio':

                    break;
            }
            $out .= '</tr>';
        }
        $out .= '<input type="submit" name="submitForm" value="Сохранить"/></table></form>';
        return $out;
    }

}