<?php

include_once 'TaskInit.php';
include_once 'CardInit.php';
include_once 'DeskInit.php';
include_once 'StageInit.php';
include_once 'ProjectInit.php';
include_once MOD . '/Project/Stage.php';
include_once MOD . '/Form/Form.php';

class Initializer
{
    var $project;
    var $stages = [];
    var $desks = [];
    var $cards = [];
    var $tasks = [];

    function __construct($project, $stages, $desks, $cards, $tasks)
    {
        $this->project = $project;
        $this->stages = $stages;
        $this->desks = $desks;
        $this->cards = $cards;
        $this->tasks = $tasks;
    }

    function initialize($usersid, $cityid)
    {
        $projectid;
        $stagesid = [];
        $desksid = [];
        $cardsid = [];
        $tasksid = [];

        $db = DataBase::get();

        $querry = $db->prepare('INSERT INTO task (executortype, verifiertype, text, exhours, verhours) VALUES (?, ?, ?, ?, ?)');
        foreach ($this->tasks as $taskKey => $task)
        {
            $querry->execute([$task->executortype, $task->verifiertype, $task->text, $task->exhours, $task->verhours]);
            $tasksid[$taskKey] = $db->lastInsertId();
        }

        $querry = $db->prepare('INSERT INTO card (name, tasksid, formid, chatid) VALUES (?, ?, ?, ?)');
        foreach ($this->cards as $cardKey => $card)
        {
            $cardTasksid = [];
            foreach ($card->taskKeys as $taskKey)
                $cardTasksid[] = $tasksid[$taskKey];
            $cardTasksid = implode_to_str(' ', $cardTasksid);
            //!Go there!//
            $form = Dejavu::getObject('Form',$card->form);
            $chat = Dejavu::getObject('Chat',[]);
            $querry->execute([$card->name, $cardTasksid, $form->formid, $chat->chatid]);
            $cardsid[$cardKey] = $db->lastInsertId();
        }
        $querry = $db->prepare('UPDATE card SET parentsid=?, childrenid=? WHERE cardid=?');
        foreach ($this->cards as $cardKey => $card)
        {
            $parentsid = [];
            foreach ($card->parentKeys as $parentKey)
                $parentsid[] = $cardsid[$parentKey];
            $parentsid = implode_to_str(' ', $parentsid);
            $childrenid = [];
            foreach ($card->childrenKeys as $childrenKey)
                $childrenid[] = $cardsid[$childrenKey];
            $childrenid = implode_to_str(' ', $childrenid);
            $cardid = $cardsid[$cardKey];
            $querry->execute([$parentsid, $childrenid, $cardid]);
        }

        $querry = $db->prepare('INSERT INTO desk (name, cardsid) VALUES (?, ?)');
        foreach ($this->desks as $deskKey => $desk)
        {
            $deskCardsid = [];
            foreach ($desk->cardKeys as $cardKey)
                $deskCardsid[] = $cardsid[$cardKey];
            $deskCardsid = implode_to_str(' ', $deskCardsid);
            $querry->execute([$desk->name, $deskCardsid]);
            $desksid[$deskKey] = $db->lastInsertId();
        }

        $querry = $db->prepare('INSERT INTO stage (name, desksid) VALUES (?, ?)');
        foreach ($this->stages as $stageKey => $stage)
        {
            $stageDesksid = [];
            foreach ($stage->deskKeys as $deskKey)
                $stageDesksid[] = $desksid[$deskKey];
            $stageDesksid = implode_to_str(' ', $stageDesksid);
            $querry->execute([$stage->name, $stageDesksid]);
            $stagesid[$stageKey] = $db->lastInsertId();
        }

        $querry = $db->prepare('INSERT INTO project (cityid, stagesid, usersid) VALUES (?, ?, ?)');
        $projectStagesid = [];
        foreach ($this->project->stageKeys as $stageKey)
            $projectStagesid[] = $stagesid[$stageKey];
        $projectStagesid = implode_to_str(' ', $projectStagesid);
        $usersid = implode_to_str(' ', $usersid);
        $querry->execute([strval($cityid), $projectStagesid, $usersid]);
        $projectid = $db->lastInsertId();

        foreach ($this->project->stageKeys as $stageKey)
        {
            $stage = $this->stages[$stageKey];
            foreach ($stage->deskKeys as $deskKey)
            {
                $desk = $this->desks[$deskKey];
                foreach ($desk->cardKeys as $cardKey)
                {
                    $card = $this->cards[$cardKey];
                    foreach ($card->taskKeys as $taskKey)
                    {
                        $taskid = intval($tasksid[$taskKey]);
                        $cardid = intval($cardsid[$cardKey]);
                        $deskid = intval($desksid[$deskKey]);
                        $stageid = intval($stagesid[$stageKey]);

                        $querry = $db->prepare('UPDATE task SET cardid=?, deskid=?, stageid=?, projectid=? '
                                . 'WHERE taskid=?');
                        $querry->execute([$cardid, $deskid, $stageid, $projectid, $taskid]);
                    }
                }
            }
        }

        $firstStage = Dejavu::getObject('Stage',intval($stagesid[$this->project->stageKeys[0]]));
        $project=Dejavu::getObject('Project', intval($projectid));
        $firstStage->activate($project);

        return $projectid;
    }

}
