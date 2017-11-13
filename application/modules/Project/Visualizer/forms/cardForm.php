<style>
    .panel-body {
        background:#f5f5f5;
    }

    .chat{
        background:#f5f5f5;
        border-radius:5px;
        padding:25px;
    }
    
    .message {
        padding: 10px 30px;
    }

    .message-text {
        color: #333;
        font-size: 19px;
        padding: 5px 5px;
        word-wrap: break-word;
    }

    .message-bottom {
        color: #555;
        font-size: 14px;
        padding:10px 0;
    }
</style>

<div class="container-fluid">

    <div class="row">
        
        <div class="col-sm-6 col-sm-offset-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="panel-title text-center">
                        <h2><?php echo $card->name; ?></h2>
                        <?php
                            if ($_SESSION['user']['type'] == 'project_manager' || $_SESSION['user']['type'] == 'development_manager') {
                                if ($card->status == "done")
                                    echo '<a class="btn btn-success" href="'.URI.'/reactivate/">Реактивировать задание</a>';
                                else if ($card->status != "inactive")
                                    echo '<a class="btn btn-success" href="'.URI.'/finish/">Завершить задание</a>';
                            } else if ($_SESSION['user']['type'] == 'coordinator') {
                                if ($card->isAppointment('project_manager'))
                                    echo '<a class="btn btn-info" href="' . URI . '/appoint_manager/">Назначить руководителя в проект</a>';
                                else if ($card->isAppointment('visiting_coach'))
                                    echo '<a class="btn btn-info" href="' . URI . '/appoint_coach/">Назначить выездного тренера</a>';
                            }
                        ?>

                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                            <?php $form = Dejavu::getObject('Form', $card->formid); ?>
                            <?php if (!$form->isEmpty()): ?>
                            <div class="col-md-9">
                                <?php echo $form->getHtml(); ?>
                            </div>
                            <?php endif; ?>
                        <?php if (!$form->isEmpty()): ?>
                        <div class="col-md-3">
                        <?php else: ?>
                        <div class="col-md-12">
                        <?php endif; ?>
                            <?php foreach ($card->tasksid as $taskid): ?>
                            <?php $task = Dejavu::getObject('Task', $taskid); ?>
                            <?php eval('?>'.TaskVis::makeForm($task, $user)); ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

              <?php
                    include_once MOD . '/Chat/Chat.php';
                    include_once MOD . '/Chat/Visualizer/ChatVis.php';
                    $chat = Dejavu::getObject('Chat', $card->chatid);
                    eval('?>'.ChatVis::makeForm($chat));
                    ?>
        </div>
        <div class="col-md-3">
            <?php
            $events = CardLogger::getHistory($card->cardid);
            foreach ($events as $event)
            {
                echo '<div class="alert ';
                switch ($event['status'])
                {
                    case 'active':
                        echo 'alert-info">';
                        echo 'Задание активировано';
                        break;
                    case 'form_upd':
                        echo 'alert-info">';
                        echo 'Форма отредактирована';
                        break;
                    case 'in_progress':
                        echo 'alert-info">';
                        echo 'Начата работа над заданием';
                        break;
                    case 'done':
                        echo 'alert-info">';
                        echo 'Задание выполнено';
                        break;
                    case 'rejected':
                        echo 'alert-danger">';
                        echo 'Задание проверенно и отклонено';
                        break;
                    case 'verified':
                        echo 'alert-success">';
                        echo 'Задание проверенно и принято';
                        break;
                }
                echo '<p class="text-muted">' . $event['date'] . '</p>';
                echo '</div>';
            }
            ?>
        </div>


        </div>
</div>


<script>
                $(document).ready(function() {
          $(window).keydown(function(event){
            if(event.keyCode == 13) {
              event.preventDefault();
              return false;
            }
          });
        });
        var begins;
                $('.auto_form input:text').keyup(function (event) {
                    event.stopPropagation();
                    event.preventDefault();

                    var data = $(this).serialize();
                <?php
                    echo "$.post('/ajax/save/$card->cardid', data);";
                ?>
                });
                $('.auto_form input:text').focusin(function (event) {
                    begins = $(this).val();
                });
                $('.auto_form input:text').focusout(function (event) {
                    if (begins != $(this).val())
                        $.post(
                            <?php
                                echo "'/ajax/form/$card->cardid',";
                            ?>
                                'form_upd=1');
                });
                $('.auto_form input:file').change(function(event){
                    event.stopPropagation();
                    event.preventDefault();

                    var formData = new FormData($(this.form)[0]);

                    var fi = this;

                    $.ajax({
                    <?php
                        echo "url: '/ajax/save/$card->cardid',";
                    ?>
                        type: 'POST',
                        data: formData,
                        async: false,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(res) {
                            $(fi).siblings('p.help-block').css('display', 'inline').html(res);
                        }
                        });
                        $.post(
                            <?php
                                echo "'/ajax/form/$card->cardid',";
                            ?>
                                'form_upd=1');

                });
                $('.auto_form input:checkbox').change(function(event){
                    event.stopPropagation();
                    event.preventDefault();

                    var formData = new FormData($(this.form)[0]);

                    $.ajax({
                    <?php
                        echo "url: '/ajax/save/$card->cardid',";
                    ?>
                        type: 'POST',
                        data: formData,
                        async: false,
                        cache: false,
                        contentType: false,
                        processData: false
                        });
                        $.post(
                            <?php
                                echo "'/ajax/form/$card->cardid',";
                            ?>
                                'form_upd=1');

                });
            </script>
