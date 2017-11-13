<style>
    .message.unchecked {
        /*background-color: #337ab7;*/
    }
</style>
<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="panel-title text-center">
            <h4>Чат для обсуждения задачи</h4>
        </div>
    </div>
    <div class="panel-body">
        <form class="chat-form"  method="post" enctype="multipart/form-data">
            <?php echo '<input type="hidden" name="chatid" value="' . $chat->chatid . '">'; ?>
            <input type="hidden" name="sendMessage" value="message">
            <div class="form-group">
                <div class="input-group">
                    <label class="input-group-addon btn btn-default btn-file">
                        Прикрепить файлы... <input name="messageData[]" type="file" style="display: none;" name="helo">
                    </label>
                    <textarea class="form-control custom-control" style="resize: none" required name="messageData[]"></textarea>
                    <span class="input-group-addon btn btn-default">
                        <span class="glyphicon glyphicon-envelope"></span>
                    </span>
                </div>
                <p class="help-block" style="display: none">(Прикреплено: <span></span>)</p>
            </div>
        </form>
        <?php
        include_once MOD . '/Chat/Visualizer/MessageVis.php';
        if (!empty($chat->messagesid))
        {
            end($chat->messagesid);
            do
            {
                echo '<tr><td>';
                $message = Dejavu::getObject('Message', current($chat->messagesid));
                eval('?>' . MessageVis::makeForm($message));
                echo '</td></tr>';
            } while (!empty(prev($chat->messagesid)));
        }
        ?>
    </div>
</div>
<script>
    $('.chat-form input:file').change(function () {
        var label = $(this).val().replace(/\\/g, '/').replace(/.*\//, '');
        var p = $(this).parent().parent().siblings('p');
        p.find('span').html(label);
        p.show();
    });
    $('.chat-form span.btn').click(function () {
        $('.chat-form').submit();
    });
    
    $('.chat-form .message.unchecked').hover(function () {
        $.post('/ajax/checkmsg/' + $(this).find('input[name="msg_id"]').val());
        $(this).removeClass('unchecked');
        console.log('kek');
    });
    
</script>

