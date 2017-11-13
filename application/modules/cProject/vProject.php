<?php

include_once MOD.'/Project/Visualizer/CardVis.php';

class vProject
{

    static $breadcrumb = '';

    static function makeBreadcrumb($names)
    {
        self::$breadcrumb .= '<style>'
                . '.breadcrumb>li+li:before '
                . '{ '
                . 'padding: 0 5px; '
                . 'color: #ccc; '
                . 'content: ">"; '
                . '} '
                . '.breadcrumb>li.active>a '
                . '{ '
                . 'pointer-events: none; '
                . 'cursor: default; '
                . 'color: inherit; '
                . '}'
                . '</style>';

        self::$breadcrumb .= '<nav class="container-fluid">';
        self::$breadcrumb .= '<ol class="breadcrumb">';

        if (empty($names))
            $active =  ' class="active" ';
        self::$breadcrumb .= '<li'.$active.'><a href="/projects">Проекты</a></li>';
        unset($active);

        if (!empty($names['c']))
        {
            self::$breadcrumb .= "<li class='active'><a href=\"/projects/new\">Новый</a></li>";
        }
        else if (!empty($names['a']))
        {
            self::$breadcrumb .= "<li><a href=\"/projects/{$names['projectid']}\">{$names['project']}</a></li>";
            self::$breadcrumb .= "<li class='active'><a href=\"/projects/{$names['projectid']}/appoint\">Назначить Руководителя проэкта</a></li>";
        }
        else if (isset($names['project']) && isset($names['stage']))
        {
            $active = isset($names['desk']) && isset($names['card']) &&
                    ($_SESSION['user']['type'] == 'franchise' || $_SESSION['user']['type'] == 'project_manager' || $_SESSION['user']['type'] == 'development_manager') ? '' : ' class="active" ';
            self::$breadcrumb .= "<li$active><a href=\"/projects/{$names['projectid']}/{$names['stageid']}\">{$names['project']} > {$names['stage']}</a></li>";

            if (isset($names['desk']) && isset($names['card']))
            {
                self::$breadcrumb .= "<li class=\"active\">"
                        . "<a href=\"/projects/{$names['projectid']}/{$names['stageid']}/{$names['deskid']}/{$names['cardid']}\">"
                        . "{$names['desk']} > {$names['card']}</a></li>";
            }
        }

        self::$breadcrumb .= '</ol>';
        self::$breadcrumb .= '</nav>';
    }

    static function appointList($users)
    {
        $breadcrumb = self::$breadcrumb;
        $pageBody = file_get_contents('forms/appointList.php', FILE_USE_include_once_PATH);

        include_once APP . '/pageTemplate.php';
    }

    static function projectsList($projects)
    {
        $breadcrumb = self::$breadcrumb;
        $pageBody = file_get_contents('forms/projectsList.php', FILE_USE_include_once_PATH);

        include_once APP . '/pageTemplate.php';
    }

    static function stagesList($stages, $usertype)
    {
        $breadcrumb = self::$breadcrumb;
        $pageBody = file_get_contents('forms/stagesList.php', FILE_USE_include_once_PATH);

        include_once APP . '/pageTemplate.php';
    }

    static function creationForm($areas, $cities)
    {
        $breadcrumb = self::$breadcrumb;
        $pageBody = file_get_contents('forms/creationForm.php', FILE_USE_include_once_PATH);

        include_once APP . '/pageTemplate.php';
    }

    static function desksList($desks)
    {
        $breadcrumb = self::$breadcrumb;
        $pageBody = file_get_contents('forms/desksList.php', FILE_USE_include_once_PATH);
        $pageHeader="<link rel='stylesheet' href='/styles/desksStyle.css'/>";


        include_once APP . '/pageTemplate.php';
    }

    static function cardsList($cards)
    {
        $breadcrumb = self::$breadcrumb;
        $pageBody = file_get_contents('forms/cardsList.php', FILE_USE_include_once_PATH);

        include_once APP . '/pageTemplate.php';
    }

    static function card($card, $taskupd)
    {
        $breadcrumb = self::$breadcrumb;
        $user = Dejavu::getObject('User', $_SESSION['user']['userid']);
        $pageBody = CardVis::makeForm($card, $user);
        if (!empty($taskupd['verifier']))
        {
            $days = $taskupd['hours'] / 24;
            $pageBody .= "<script>alert('Задание отправлено на проверку.\\nВремя на проверку $days дня/дней.\\nПроверяющий - {$taskupd['verifier']}');</script>";
        }

        include_once APP . '/pageTemplate.php';
    }

    static function makeAdminPanel($card){
        $user = Dejavu::getObject('User', $_SESSION['user']['userid']);
        $pageBody = CardVis::makeForm($card , $user);
        include_once APP . '/pageTemplate.php';

    }

}
