<?php

include_once MOD . '/Project/Project.php';
include_once MOD . '/Project/City.php';
include_once MOD . '/Project/Area.php';
include_once MOD . '/User/User.php';
include_once MOD . '/File/File.php';
include_once MOD . '/Project/Initializer/Initializer.php';
include_once MOD . '/Chat/Chat.php';
include_once MOD . '/Form/Form.php';
include_once MOD . '/Project/CardGraph.php';
include_once APP . '/suffixes.php';
include_once MOD . '/Project/CardLogger.php';

class mProject
{

    static $maxFileSize = 5 * 1024 * 1024;

    static function testForm()
    {
        $form = Dejavu::getObject('Form', 27);
        echo $form->getHtml();
    }

    /////////////////////////////     default      /////////////////////////////
    static function start()
    {
        session_start();
    }

    static function isAuthorized()
    {
        return isset($_SESSION['user']);
    }

    static function getUser()
    {
        $user = Dejavu::getObject('User', $_SESSION['user']['userid']);
        return $user;
    }

    static function getUserType()
    {
        return mProject::getUser()->type;
    }

    ////////////////////////////////////////////////////////////////////////////
    /////////////////////////////      index       /////////////////////////////
    
    static function catchSelectForm()
    {
        if (isset($_POST['selectFinished'])) return 'done';
        if (isset($_POST['selectProgress'])) return 'progress';
        return 'all';
    }
    
    static function isInNeed($projectid)
    {
        $str = self::catchSelectForm();
        $project = Dejavu::getObject('Project', $projectid);
        if($str == 'all') return true;
        if($str == 'done') return $project->finished;
        if($str == 'progress') return !$project->finished;
    }
    static function getProjects()
    {
        $projects = [];
        $user =Dejavu::getObject('User', $_SESSION['user']['userid']);
        $projectsid = $user->projectsid;

        foreach ($projectsid as $counter => $projectid)
        {
            if (!$user->isThereWorkToDo($projectid))
                continue;
            if (!self::isInNeed($projectid))
                continue;
            $project = Dejavu::getObject('Project', $projectid);
            $city = Dejavu::getObject('City', $project->cityid);
            $stages = self::getStages($projectid);

            $name = $city->name;
            
            if (($address = mProject::getAddress($project)) != '  ')
                $name .= '<br>' . $address;

            $projects[] = ['id' => $projectid, 'name' => 'Проект' . ($counter + 1), 'city' => $name, 'stages' => $stages, 'curstage' => $project->current_stage];
        }
        return $projects;
    }


    static function typeInProject($project, $type)
    {
        foreach ($project->usersid as $userid)
        {
            $user = Dejavu::getObject('User', intval($userid));
            
            if ($user->type == $type)
                return true;
        }
        return false;
    }

    static function getAddress($project)
    {
        $stage = Dejavu::getObject('Stage', $project->stagesid[1]);
        $desk = Dejavu::getObject('Desk', $stage->desksid[0]);
        $card = Dejavu::getObject('Card', $desk->cardsid[0]);
        $form = Dejavu::getObject('Form', $card->formid);
        $input1 = Dejavu::getObject('Input', $form->inputsid[4]);
        $input2 = Dejavu::getObject('Input', $form->inputsid[5]);
        $input3 = Dejavu::getObject('Input', $form->inputsid[6]);
        $text = $input1->options['text'] . ' ' . $input2->options['text'] . ' ' . $input3->options['text'];

        return $text;
    }

    static function getFullAddress($project)
    {
        $city = Dejavu::getObject('City', $project->cityid);

        return $city->name . ' ' . mProject::getAddress($project);
    }

    ////////////////////////////////////////////////////////////////////////////
    /////////////////////////////      delete       ////////////////////////////
    static function deleteProject($projectid)
    {
        $project = Dejavu::getObject('Project', $projectid);
        
        $usersid = $project->usersid;
        foreach ($usersid as $userid)
        {
            $user = Dejavu::getObject('User', $userid);
            foreach ($user->projectsid as $key => $id)
            {
                if ($projectid == $id)
                    unset($user->projectsid[$key]);
            }
        }

        $project->delete();
    }

    ////////////////////////////////////////////////////////////////////////////
    /////////////////////////////      appoint       ///////////////////////////
    static function getVerifiers($_projectid, $type)
    {
        $db = DataBase::get();
        $querry = $db->prepare("SELECT userid FROM user WHERE type='".$type."'");
        $querry->execute([]);

        $usersList = [];

        $usersid = $querry->fetchAll();
        foreach ($usersid as $userid)
        {
            $user = Dejavu::getObject('User', intval($userid[0]));

            $found = false;

            foreach ($user->projectsid as $projectid)
            {
                if ($projectid == $_projectid)
                {
                    $found = true;
                    break;
                }
            }

            if (!$found)
            {
                $stat = $user->projectsStat();
                $active = $stat['all'] - $stat['done'];
                $usersList[] = ['name' => ($user->fname) . ' ' . ($user->sname),
                    'projects' => $active, 'userid' => $user->userid];
            }
        }

        return $usersList;
    }

    static function isAppointSubmit()
    {
        return isset($_POST['user']);
    }

    static function appoint($projectid, $cardid, $type)
    {
        $userid = intval($_POST['userid']);
        $user = Dejavu::getObject('User', $userid);

        if ($user->type != $type)
            return;

        $card = Dejavu::getObject('Card', $cardid);

        if (!$card->isAppointment($type))
            return;

        $project = Dejavu::getObject('Project', $projectid);

        if (!$user->isEmpty())
        {
            $project->addUser($userid);
            $user->addProject($projectid);
        }
    }

    static function isCoordinator()
    {
        return mProject::getUserType() == 'coordinator';
    }

    ////////////////////////////////////////////////////////////////////////////
    /////////////////////////////      create       ////////////////////////////
    static function isCityFormSet()
    {
        return isset($_POST['cityid']);
    }

    static function isAreaFormSet()
    {
        return isset($_POST['areaid']);
    }

    static function isAreaSubmit()
    {
        return isset($_POST['area']);
    }

    static function isCitySubmit()
    {
        return isset($_POST['city']);
    }

    static function getAreaId()
    {
        return intval($_POST['areaid']);
    }

    private static function compareAreas($area1, $area2)
    {
        return strcmp($area1['name'], $area2['name']);
    }

    private static function compareCities($city1, $city2)
    {
        return strcmp($city1['name'], $city2['name']);
    }

    static function getAreas()
    {
        $db = DataBase::get();
        $querry = $db->prepare('SELECT * FROM area');
        $querry->execute([]);

        $areas = [];
        while ($area = $querry->fetch())
        {
            $area['areaid'] = intval($area['areaid']);
            $areas[] = $area;
        }
        usort($areas,"self::compareAreas");
        return $areas;
    }

    static function getCities($areas)
    {
        $db = DataBase::get();
        $areasid = [];
        foreach ($areas as $area)
            $areasid[] = $area['areaid'];
        $placehold = implode(',', $areasid);
        $querry = $db->prepare("SELECT * FROM city WHERE areaid IN ($placehold)");
        $querry->execute([]);

        $cities = [];
        while ($city = $querry->fetch())
        {
            $city['cityid'] = intval($city['cityid']);
            $cities[intval($city['areaid'])][] = $city;
        }
        foreach ($cities as $areaid => $cities_in_area)
        {
            usort($cities[$areaid], "self::compareCities");
        }

        return $cities;
    }

    static function createProject()
    {
        include_once MOD . '/Project/Initializer/Config/config.php';
        $user = self::getUser();
        $db = DataBase::get();
        // Searching for coordinator
        $querry = $db->prepare('SELECT userid FROM user WHERE type="coordinator" LIMIT 1');
        $querry->execute();
        $coordid = intval($querry->fetch()['userid']);
        // Searching for admin
        $querry = $db->prepare('SELECT userid FROM user WHERE type="admin" LIMIT 1');
        $querry->execute();
        $adminid = intval($querry->fetch()['userid']);

        $franchid = $user->userid;
        $projectid = $initializer->initialize([$franchid, $coordid, $adminid], $_POST['cityid_' . $_POST['areaid']]);
        $user->addProject($projectid);
        // Adding project to coordinator
        $coordinator = Dejavu::getObject('User', $coordid);
        $coordinator->addProject($projectid);
        // Adding project to admin
        $admin = Dejavu::getObject('User', $adminid);
        $admin->addProject($projectid);

        return $projectid;
    }

    ////////////////////////////////////////////////////////////////////////////
    /////////////////////////////      project       ///////////////////////////
    static function checkProjectAccess($projectid)
    {
        $user = self::getUser();

        if (mProject::getUserType() == "admin")
            return true;
        else if (in_array($projectid, $user->projectsid))
        {
            $project = Dejavu::getObject('Project', $projectid);
            return $project->isAvail($_SESSION['user']['type']);
        }
        else
            return false;
    }

    static function checkStagesAvail($projectid)
    {
        foreach ($project->stagesid as $stageid)
        {
            if (self::checkDesksAvail($stageid))
                return true;
        }
        return false;
    }

    static function getStages($projectid)
    {
        $stages = [];

        $project = Dejavu::getObject('Project', $projectid);
        $stagesid = $project->stagesid;

        foreach ($stagesid as $index => $stageid)
        {
            $stage = Dejavu::getObject('Stage', $stageid);
            if ($stage->isAvail($_SESSION['user']['type']) || $_SESSION['user']['type'] == 'project_manager' || $_SESSION['user']['type'] == 'development_manager' || $_SESSION['user']['type']=='franchise')
            {
                if ($stage->status != 'done')
                {
                    $cardGraph = new CardGraph;
                    $stageIntTime = $cardGraph->maxWay($stageid) / 24;
                    $stageTime = $stageIntTime . " " . suffix($stageIntTime, ['дней', 'день', 'дня']);
                }
                else
                    $stageTime = '';
                $stages[] = ['name' => $stage->name, 'progress' => self::stageProgress($stage),
                    'status' => $stage->status, 'id' => $stageid, 'time' => $stageTime];
            }
        }

        return $stages;
    }

    static function stageProgress($stage)
    {
        return (int) (100 * $stage->progress());
    }

    ////////////////////////////////////////////////////////////////////////////
    /////////////////////////////      stage       /////////////////////////////
    static function checkStageAccess($_projectid, $_stageid)
    {
        if ($_SESSION['user']['type'] == 'project_manager' || $_SESSION['user']['type'] == 'development_manager' || $_SESSION['user']['type'] == 'franchise')
            return true;
        if (!self::checkProjectAccess($_projectid))
            return false;

        $stage = Dejavu::getObject('Stage', $_stageid);
        if ($stage->status == 'inactive')
            return false;

        $project = Dejavu::getObject('Project', $_projectid);
        $stagesid = $project->stagesid;

        foreach ($stagesid as $stageid)
        {
            if ($stageid == $_stageid)
            {
                $stage = Dejavu::getObject('Stage', $stageid);
                return $stage->isAvail($_SESSION['user']['type']);
            }
        }

        return false;
    }

    static function getDesks($stageid)
    {
        $desks = [];

        $stage = Dejavu::getObject('Stage', $stageid);
        $desksid = $stage->desksid;

        foreach ($desksid as $deskid)
        {
            $desk = Dejavu::getObject('Desk', $deskid);
            $cards = self::getCards($deskid);

            if ($desk->isAvail($_SESSION['user']['type']) || $_SESSION['user']['type'] == 'project_manager' || $_SESSION['user']['type'] == 'development_manager' || $_SESSION['user']['type'] == 'franchise')
            {
                $desks[] = ['name' => $desk->name, 'id' => $deskid, 'cards' => $cards];
            }
        }
        return $desks;
    }

    ////////////////////////////////////////////////////////////////////////////
    /////////////////////////////       desk       /////////////////////////////
    static function checkDeskAccess($_projectid, $_stageid, $_deskid)
    {
        if ($_SESSION['user']['type'] == 'project_manager' || $_SESSION['user']['type'] == 'development_manager' || $_SESSION['user']['type'] == 'franchise')
            return true;
        if (!self::checkStageAccess($_projectid, $_stageid))
            return false;

        $stage = Dejavu::getObject('Stage', $_stageid);
        $desksid = $stage->desksid;

        foreach ($desksid as $deskid)
        {
            if ($deskid == $_deskid)
            {
                $desk = Dejavu::getObject('Desk', $deskid);
                return $desk->isAvail($_SESSION['user']['type']);
            }
        }

        return false;
    }

    static function getCards($deskid)
    {
        $cards = [];

        $desk = Dejavu::getObject('Desk', $deskid);
        $cardsid = $desk->cardsid;

        foreach ($cardsid as $cardid)
        {
            $card = Dejavu::getObject('Card', $cardid);

            if ($card->isAvail($_SESSION['user']['type']) || $_SESSION['user']['type'] == 'project_manager' || $_SESSION['user']['type'] == 'development_manager')
            {
                if (mProject::waitVerifier($card))
                    $cards[] = ['name' => $card->name, 'id' => $cardid, 'status' => 'wait','reactivated'=>$card->reactivated];
                else if (mProject::cardRejected($card))
                    $cards[] = ['name' => $card->name, 'id' => $cardid, 'status' => 'rejected','reactivated'=>$card->reactivated];
                else
                    $cards[] = ['name' => $card->name, 'id' => $cardid, 'status' => $card->status,'reactivated'=>$card->reactivated];
                if (!$card->isAvail('franchise'))
                    end($cards)['app_task'] = true;
            }
            else if ($_SESSION['user']['type'] == 'franchise')
            {
                $cards[] = ['name' => $card->name, 'id' => $cardid, 'status' => $card->status,'reactivated'=>$card->reactivated, 'fr'=>true, 'app_task' => true];
            }
            $task = Dejavu::getObject('Task', $card->tasksid[0]);
            if ('in_progress' == $task->status)
            {
                $wap = end($cards);
                $wap['status'] = 'in_progress';
                array_pop($cards);
                $cards[] = $wap;
            }
        }

        return $cards;
    }

    static function waitVerifier($card)
    {
        foreach ($card->tasksid as $taskid)
        {
            $task = Dejavu::getObject('Task', $taskid);

            if ($task->status == 'done')
                return true;
        }

        return false;
    }

    static function cardRejected($card)
    {
        foreach ($card->tasksid as $taskid)
        {
            $task = Dejavu::getObject('Task', $taskid);

            if ($task->status == 'rejected')
                return true;
        }

        return false;
    }

    ////////////////////////////////////////////////////////////////////////////
    /////////////////////////////       card       /////////////////////////////
    static function checkCardAccess($_projectid, $_stageid, $_deskid, $_cardid)
    {
        if ($_SESSION['user']['type'] == 'project_manager' || $_SESSION['user']['type'] == 'development_manager')
            return true;
        $card = Dejavu::getObject('Card', $_cardid);
        if($_SESSION['user']['type'] == 'franchise' && $card->status == 'done')
            return true;
        if (!self::checkDeskAccess($_projectid, $_stageid, $_deskid))
            return false;

        $desk = Dejavu::getObject('Desk', $_deskid);
        $cardsid = $desk->cardsid;

        foreach ($cardsid as $cardid)
        {
            if ($cardid == $_cardid)
            {
                $card = Dejavu::getObject('Card', $cardid);
                return $card->isAvail($_SESSION['user']['type']);
            }
        }
        return false;
    }

    static function checkPMAccess($cardid)
    {
        $card = Dejavu::getObject('Card', $cardid);
        return $_SESSION['user']['type'] != 'project_manager' || $card->status != 'inactive' ;
    }

    static function catchCardForm()
    {
        return isset($_POST['submitForm']);
    }

    static function saveCardForm($cardid)
    {
//        $card = Dejavu::getObject('Card', $cardid);
//        $form = Dejavu::getObject('Form', $card->formid);
//        foreach ($form->inputsid as $inputid)
//        {
//            $input = Dejavu::getObject('Input', $inputid);
//            $name = $input->name;
//            if (empty($_FILES[$name]))
//            {
//                $input->setVal($_POST[$name]);
//            }
//            else
//            {
//                if ($_FILES[$name]['error'] || $_FILES[$name]['size'] > self::$maxFileSize)
//                    continue;
//                $fileData = $_FILES[$name];
//                $fileData['data'] = file_get_contents($fileData['tmp_name']);
//                $fileName = $fileData['name'];
//                $file = Dejavu::getObject('File', $fileData);
//                $file->save();
//                $input->setVal(['href' => '/download/' . $file->fileid, 'fileName' => $fileName]);
//            }
//        }
    }

    static function catchTaskForm()
    {
        return isset($_POST['submitStatus']);
    }

    static function updateTask($cardid)
    {
        $task = Dejavu::getObject('Task', intval($_POST['taskid']));
        switch ($_POST['submitStatus'])
        {
            case 'Приступить':
                if ('in_progress' == $task->status)
                    break;
                $task->start();
                CardLogger::log($cardid, 'in_progress');
                return [ 'status' => $task->status];
                break;

            case 'Выполнить':
                if ('done' == $task->status)
                    break;
                $task->execute();
                CardLogger::log($cardid, 'done');
                if ($task->verifierid)
                {
                    $ver = Dejavu::getObject('User', $task->verifierid);
                    $arr = ['hours' => $task->verhours, 'verifier' => $ver->fname . ' ' . $ver->tname . '(' . $ver->mail . ')'];
                }
                return $arr;
                break;

            case 'Подтвердить':
                if ('verified' == $task->status)
                    break;
                $task->verify(true);
                CardLogger::log($cardid, 'verified');
                break;

            case 'Отклонить':
                if ('rejected' == $task->status || 'active' == $task->status)
                    break;
                $task->verify(false);
                CardLogger::log($cardid, 'rejected');
                break;
        }
    }

    static function catchChatForm()
    {
        return isset($_POST['sendMessage']);
    }

    static function saveData()
    {
        $datasid = [];
        if (!empty($_POST['messageData'][0]))
        {
            $data = Dejavu::getObject('Data', ['type' => 'text', 'data' => $_POST['messageData'][0]]);
            $data->save();
            $datasid[] = $data->dataid;
        }
        if (!empty($_FILES['messageData']['size'][0]))
        {
            $files = $_FILES['messageData'];
            $size = count($files['name']);
            for ($i = 0; $i < $size; $i++)
            {
                $file = [];
                $file['size'] = $files['size'][$i];
                if ($file['size'] > self::$maxFileSize)
                    continue;
                $file['name'] = $files['name'][$i];
                $file['type'] = $files['type'][$i];
                $file['data'] = file_get_contents($files['tmp_name'][$i]);
                $file = Dejavu::getObject('File', $file);
                $data = Dejavu::getObject('Data', ['type' => 'file', 'data' => '<a href="/download/' . $file->fileid . '">' . $file->name . '</a>']);
                $datasid[] = $data->dataid;
            }
        }
        return $datasid;
    }

    static function saveMessage($datasid)
    {
        $message = Dejavu::getObject('Message', ['userid' => $_SESSION['user']['userid'], 'datasid' => $datasid]);
        $message->save();
        $chat = Dejavu::getObject('Chat', intval($_POST['chatid']));
        $chat->addMessage($message->messageid);
    }

    static function getCard($cardid)
    {
        $card = Dejavu::getObject('Card', $cardid);

        return $card;
    }

    static function getAllCards($projectid) // for non key roles
    {
        $user = Dejavu::getObject('User', $_SESION['user']['userid']);
        return $user->getAwaitingCards($projectid);
    }

    static function getBreadcrumbNames($projectid, $stageid, $deskid, $cardid)
    {
        $names = [];
        $db = DataBase::get();

        if ($projectid == 'c')
        {
            $names['c'] = true;
        }
        else if (isset($projectid))
        {
            $querry = $db->prepare("SELECT name FROM city WHERE cityid=(SELECT cityid FROM project WHERE projectid=?) LIMIT 1");
            $querry->execute([$projectid]);
            $names['project'] = $querry->fetch()['name'];
            $names['projectid'] = $projectid;

            if ($stageid == 'a')
            {
                $names['a'] = true;
            }
            else if (isset($stageid))
            {
                $querry = $db->prepare("SELECT name FROM stage WHERE stageid=? LIMIT 1");
                $querry->execute([$stageid]);
                $names['stage'] = $querry->fetch()['name'];
                $names['stageid'] = $stageid;

                if (isset($deskid))
                {
                    $querry = $db->prepare("SELECT name FROM desk WHERE deskid=? LIMIT 1");
                    $querry->execute([$deskid]);
                    $names['desk'] = $querry->fetch()['name'];
                    $names['deskid'] = $deskid;

                    if (isset($cardid))
                    {
                        $querry = $db->prepare("SELECT name FROM card WHERE cardid=? LIMIT 1");
                        $querry->execute([$cardid]);
                        $names['card'] = $querry->fetch()['name'];
                        $names['cardid'] = $cardid;
                    }
                }
            }
        }

        return $names;
    }

    ////////////////////////////////////////////////////////////////////////////
    /////////////////////////////   reactivation   /////////////////////////////

    static function reactivate($cardid)
    {
        $card = Dejavu::getObject('Card',$cardid);
        if ($card->status != "done")
            return false;
        $card->status = "active";
        $card->reactivated="true";
        $task =Dejavu::getObject('Task',$card->tasksid[0]);
        $task->status = "active";
        self::deactivateChildren($card);
    }

    static function deactivateChildren($card)
    {
        foreach ($card->childrenid as $childid)
        {
            $child = Dejavu::getObject('Card',$childid);
            if ($child->status == "done")
            {
                self::deactivateChildren($child);
                $child->reactivated="true";
            }
                $child->status = "inactive";
        }
    }

    public static function finishCard($cardid)
    {
        $card=Dejavu::getObject("Card", $cardid);
        $task=Dejavu::getObject("Task", $card->tasksid[0]);
        $task->status="done";
        $task->verify(true);

    }

}
