<?php

include_once 'mProject.php';
include_once 'vProject.php';

class cProject
{
    static function form()
    {
        mProject::start();
        mProject::testForm();
    }

    static function index()
    {
        mProject::start();
        if (mProject::isAuthorized())
        {
            vProject::makeBreadcrumb(mProject::getBreadcrumbNames());
            vProject::projectsList(mProject::getProjects());
        }
        else
            unauthorized();
    }

    static function adminPage()
    {
        mProject::start();
        if (mProject::isAuthorized())
        {
            if (mProject::getUserType() == "admin")
                vProject::makeAdminPanel(mProject::getCard(1));
            else http_response_code(404);
        }
        else
            unauthorized();
    }

    static function delete($projectid)
    {
        mProject::start();
        $projectid = intval($projectid);

        if (mProject::getUserType() == "admin")
        {
            if (mProject::checkProjectAccess($projectid))
                mProject::deleteProject($projectid);
        }
        else
            unauthorized();

        header('Location: /projects');
    }

    static function appoint($projectid, $stageid, $deskid, $cardid, $type)
    {
        mProject::start();
        $projectid = intval($projectid);
        $stageid = intval($stageid);
        $deskid = intval($deskid);
        $cardid = intval($cardid);

        if (mProject::isAuthorized())
        {
            if (mProject::isCoordinator() && mProject::checkCardAccess($projectid, $stageid, $deskid, $cardid))
            {
                if (mProject::isAppointSubmit())
                {
                    mProject::appoint($projectid, $cardid, $type);
                    header('Location: /projects');
                }
                else
                {
                    vProject::makeBreadcrumb(mProject::getBreadcrumbNames($projectid, 'a'));
                    vProject::appointList(mProject::getVerifiers($projectid, $type));
                }
            } else
                header('Location: /projects');
        }
        else
            unauthorized();
    }

    static function create()
    {
        mProject::start();
        if (mProject::isAuthorized())
        {
            if (mProject::isCitySubmit())
            {
                $projectid = mProject::createProject();
                header('Location: /projects/');
            }
            else
            {
                $areas = mProject::getAreas();
                vProject::makeBreadcrumb(mProject::getBreadcrumbNames('c'));
                vProject::creationForm($areas, mProject::getCities($areas));
            }
        }
        else
            unauthorized();
    }

    static function project($projectid)
    {
        $projectid = intval($projectid);

        mProject::start();
        if (mProject::isAuthorized())
        {
            if (mProject::checkProjectAccess($projectid))
            {
                vProject::makeBreadcrumb(mProject::getBreadcrumbNames($projectid));
                vProject::stagesList(mProject::getStages($projectid), mProject::getUserType());
            }
            else
                http_response_code(404);
        }
        else
            unauthorized();
    }

    static function stage($projectid, $stageid)
    {
        $projectid = intval($projectid);
        $stageid = intval($stageid);

        mProject::start();
        if (mProject::isAuthorized())
        {
            if (mProject::checkStageAccess($projectid, $stageid))
            {
                vProject::makeBreadcrumb(mProject::getBreadcrumbNames($projectid, $stageid));
                vProject::desksList(mProject::getDesks($stageid));
            }
            else
                http_response_code(404);
        }
        else
            unauthorized();
    }

    static function desk($projectid, $stageid, $deskid)
    {
        http_response_code(404);
        return;
        $projectid = intval($projectid);
        $stageid = intval($stageid);
        $deskid = intval($deskid);

        mProject::start();
        if (mProject::isAuthorized())
        {
            if (mProject::checkDeskAccess($projectid, $stageid, $deskid))
            {
                vProject::makeBreadcrumb(mProject::getBreadcrumbNames($projectid, $stageid, $deskid));
                vProject::cardsList(mProject::getCards($deskid));
            }
            else
                http_response_code(404);
        }
        else
            unauthorized();
    }

    static function card($projectid, $stageid, $deskid, $cardid)
    {
        $projectid = intval($projectid);
        $stageid = intval($stageid);
        $deskid = intval($deskid);
        $cardid = intval($cardid);

        mProject::start();
        if (mProject::isAuthorized())
        {
            if (mProject::checkCardAccess($projectid, $stageid, $deskid, $cardid))
            {
                if (mProject::checkPMAccess($cardid) && mProject::catchCardForm())
                {
                    mProject::saveCardForm($cardid);
                }
                if (mProject::checkPMAccess($cardid) && mProject::catchTaskForm())
                {
                    $taskUpd = mProject::updateTask($cardid);
                    if (!($_SESSION['user']['type'] == 'project_manager' || $_SESSION['user']['type'] == 'franchise' ||
                            $_SESSION['user']['type'] == 'development_manager') && $taskUpd['status'] != 'in_progress')
                        header("Location: /projects");
                }
                if (mProject::checkPMAccess($cardid) && mProject::catchChatForm())
                {
                    $datasid = mProject::saveData();
                    mProject::saveMessage($datasid);
                }
                vProject::makeBreadcrumb(mProject::getBreadcrumbNames($projectid, $stageid, $deskid, $cardid));

                $card = Dejavu::getObject('Card', $cardid);
                vProject::card($card, $taskUpd);
            }
            else
                http_response_code(404);
        }
        else
            unauthorized();
    }

    static function reactivate($projectid, $stageid, $deskid, $cardid)
    {
        mProject::start();
        mProject::reactivate((int)$cardid);
        header('Location: /projects/'.$projectid.'/'.$stageid.'/'.$deskid.'/'.$cardid);
    }

    static function finish($projectid, $stageid, $deskid, $cardid)
    {
        mProject::start();
        mProject::finishCard((int)$cardid);
        header('Location: /projects/'.$projectid.'/'.$stageid);
    }


}
