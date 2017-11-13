<?php if ($task->status != 'inactive' || $_SESSION['user']['type'] == 'project_manager' || $_SESSION['user']['type'] == 'development_manager'): ?>
    <?php if ($task->status == 'active'): ?>
    <div class="alert alert-info">
    <?php elseif ($task->status == 'in_progress'): ?>
    <div class="alert alert-info">
    <?php elseif ($task->status == 'rejected'): ?>
    <div class="alert alert-danger">
    <?php elseif ($task->status == 'done'): ?>
    <div class="alert alert-info">
    <?php elseif ($task->status == 'verified'): ?>
    <div class="alert alert-success">
    <?php elseif ($task->status == 'inactive'): ?>
    <div class="alert alert-info">
    <?php endif; ?>
        <?php echo '<p>'.$task->text.'</p>'; ?>
        <?php
            if ($_SESSION['user']['userid'] == $task->executorid)
                $exec = "Вы";
            else
            {
                $userF = Dejavu::getObject('User', intval($task->executorid));
                $exec = '<a href="/user/' . $task->executorid . '">' . $userF->fname . ' ' . $userF->tname . '</a>';
            }
            if ($task->verifiertype != 'none')
            {
                if ($_SESSION['user']['userid'] == $task->verifierid)
                    $verif = "Вы";
                else
                {
                    $userF = Dejavu::getObject('User', intval($task->verifierid));
                    $verif = '<a href="/user/' . $task->verifierid . '">' . $userF->fname . ' ' . $userF->tname . '</a>';
                }
            }
        ?>

        <?php 
            echo "<p class='text-muted'>Исполнитель: $exec</p>";
            if ($verif)
                echo "<p class='text-muted'>Проверяющий: $verif</p>";
        ?>
        <form class="form-inline" action="" method="post">
            <?php echo '<input type="hidden" name="taskid" value="' . $task->taskid . '">'; ?>
            <div class="form-group" style="margin-bottom: 0px">
                <?php if (($task->status == 'active' || $task->status == 'rejected') && $user->type == $task->executortype): ?>
                    <input class="form-control btn btn-sm btn-success" type="submit" name="submitStatus" value="Приступить">
                <?php elseif (($task->status == 'in_progress') && $user->type == $task->executortype): ?>
                    <input class="form-control btn btn-sm btn-success" type="submit" name="submitStatus" value="Выполнить">
                <?php elseif ($task->status == 'done' && $user->type == $task->verifiertype): ?>
                    <input class="form-control btn btn-sm btn-success" type="submit" name="submitStatus" value="Подтвердить">
                    <input class="form-control btn btn-sm btn-danger" type="submit" name="submitStatus" value="Отклонить">
                <?php endif; ?>
            </div>
        </form>
    </div>
<?php else: ?>
    Недоступно
<?php endif; ?>