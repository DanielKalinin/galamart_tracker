
<div class="row">
<?php
    foreach ($desks as $desk)
    {
        echo '<div class="col-lg-3 col-md-4 col-sm-6">';
        echo '<div class="panel panel-primary">';
        echo '<div class="panel-heading"><div class="panel-title">'.$desk['name'].'</div></div>';

        echo '<div class="panel-body">';

        foreach ($desk['cards'] as $card)
        {
            if ((($card['status'] != 'inactive' || $_SESSION['user']['type'] == 'project_manager' || $_SESSION['user']['type'] == 'development_manager' ) && !$card['fr']) || ($_SESSION['user']['type'] == 'franchise' && $card['status'] == 'done'))
                echo '<a style="display: block" href="'.URI.'/'.$desk['id'].'/'.$card['id'].'"';
            else
                echo '<div';

            if ($card['status'] == 'wait')
                echo ' class="alert alert-warning';
            else if ($card['status'] == 'rejected')
                echo ' class="alert alert-danger';
            else if ($card['status'] == 'in_progress')
                echo ' class="alert alert-pink';
            else if ($card['status'] == 'unacc')
                echo ' class="alert alert-info';
            else if ($card['status'] == 'done')
                echo ' class="alert alert-success';
            else if ($card['status'] != 'inactive')
                echo ' class="alert alert-info';
            else
                echo ' class="alert alert-info';

            
            echo '">';

            echo $card['name'];

            if ($card['status'] == 'inactive' || $card['fr'])
                echo ' <span class="glyphicon glyphicon-lock"></span>';
            
            
            if($card['reactivated']=='true')
                echo ' <span class="glyphicon glyphicon-refresh"></span>';

            if ($card['app_task'])
                echo '<p class="text-muted">Задача выполняется сотрудниками Галамарт</p></span>';

           if ((($card['status'] != 'inactive' || $_SESSION['user']['type'] == 'project_manager' || $_SESSION['user']['type'] == 'development_manager' ) && !$card['fr']) || ($_SESSION['user']['type'] == 'franchise' && $card['status'] == 'done'))
                echo '</a>';
            else
                echo '</div>';
        }
        echo '</div></div>';
        echo '</div>';
    }
?>
</div>