<div class="container-fluid">
    <?php if ($_SESSION['user']['type'] == 'franchise'): ?>
    <a class="btn btn-success" style="margin-bottom: 5px" href="/projects/new">Создать проект</a>
<?php endif; ?>
    <?php if ($_SESSION['user']['type'] == 'franchise'|| $_SESSION['user']['type'] == 'development_manager'|| $_SESSION['user']['type'] == 'project_manager'): ?>
    <div class="row" style="margin-bottom: 5px">
        <form method="POST" class="form-inline col-sm-12">
            <div class="form-group">
                <input type="submit" name="selectAll" class="btn btn-success form-control" value="Все проекты">
            </div>
            <div class="form-group">
                <input type="submit" name ="selectFinished" class="btn btn-success form-control" value="Законченные проекты">
            </div>
            <div class="form-group">
                <input type="submit" name= "selectProgress" class="btn btn-success form-control" value="Проекты в работе">
            </div>
        </form>
    </div>
    
<?php endif; ?>
<?php
    echo '<div class="row">';
    foreach ($projects as $project)
    {
        echo '<div class="col-lg-3 col-md-4 col-sm-6">';
        echo '<div class="panel panel-primary">';
        echo '<div class="panel-heading"><div class="panel-title pull-left">'
        . '<span class="glyphicon glyphicon-home"></span> '.$project['city'].'</div>';

        echo '<div class="panel-title pull-right"">';

        
        echo '</div><div class="clearfix"></div></div>';

        
        echo '<div class="panel-body">';
        

        if ($_SESSION['user']['type'] == 'project_manager' || $_SESSION['user']['type'] == 'development_manager'
                 || $_SESSION['user']['type'] == 'franchise')
        {
            foreach ($project['stages'] as $key => $stage)
            {
                if ($stage['status'] != 'inactive' || $_SESSION['user']['type'] == 'project_manager' || $_SESSION['user']['type'] == 'development_manager')
                    echo '<a style="display: block" href="'.URI.'/'.$project['id'].'/'.$stage['id'].'"';
                else
                    echo '<div';

                if ($stage['status'] == 'done')
                    echo ' class="alert alert-success">';
                else if ($stage['status'] == 'active')
                    echo ' class="alert alert-info">';
                else
                    echo ' class="alert alert-info">';

                echo $stage['name'];

                if ($stage['status'] == 'active')
                {
                    if ($stage['progress'] < 10)
                    {
                        $on = '';
                        $un = $stage['progress'].'%';
                    }
                    else
                    {
                        $on = $stage['progress'].'%';
                        $un = '';
                    }
                    echo '<div style="display: block; margin: 0;" class="progress text-center">'
                        . '<div class="progress-bar progress-bar-success" style="width: '.$stage['progress'].'%">'.$on.'</div>'.$un.'</div>';
                }
                else if ($stage['status'] != 'done')
                    echo '<div style="display: block; margin: 0;" class="progress text-center">'
                        . '<div class="progress-bar" style="width: 0%"></div><span class="glyphicon glyphicon-lock"></span></div>';
                    if ($stage['status']!='done')echo "Примерно:".$stage['time'];
                if ($stage['status'] != 'inactive' || $_SESSION['user']['type'] == 'project_manager' || $_SESSION['user']['type'] == 'development_manager')
                    echo '</a>';
                else
                    echo '</div>';
            }
        }
        else
        {
            $user = Dejavu::getObject('User', $_SESSION['user']['userid']);
            $await = $user->getAwaitingCards($project['id']);
            foreach ($await as $card)
            {
                if ($card['status'] != 'inactive')
                    echo '<a style="display: block" href="/projects/' . $project['id'] . '/' . $card['stageid'] . '/' .
                        $card['deskid'] . '/' . $card['cardid'] . '"';
                else
                    echo '<div';

                if ($card['status'] == 'wait')
                    echo ' class="alert alert-warning">';
                else if ($card['status'] == 'rejected')
                    echo ' class="alert alert-danger">';
                else if ($card['status'] == 'done')
                    echo ' class="alert alert-success">';
                else if ($card['status'] == 'in_progress')
                    echo ' class="alert alert-pink">';
                else if ($card['status'] != 'inactive')
                    echo ' class="alert alert-info">';
                else
                    echo ' class="alert alert-info">';

                echo $card['name'];
                
                if($card['reactivated']=='true')
                    echo ' <span class="glyphicon glyphicon-refresh"></span>';

                if ($card['status'] != 'inactive')
                    echo '</a>';
                else
                    echo '</div>';
            }
        }
        echo '</div></div>';
    echo '</div>';
    }
    echo '</div>';
?>
</div>
