    <?php
    include_once MOD . '/User/User.php';
    $chatuser = Dejavu::getObject('User', $message->userid);
    ?>

<div class="message alert alert-info <?php if (!$message->checked) echo 'unchecked'; ?>">
    <input name="msg_id" hidden value="<?php echo $message->messageid; ?>">
    <input name="author_id" hidden value="<?php echo $message->userid; ?>">
    <div class="row">
        <div class="message-text col-sm-12 text-left">

            <?php
            include_once MOD . '/Chat/Data.php';
            include_once MOD . '/Chat/Visualizer/DataVis.php';
            
            foreach ($message->datasid as $dataid)
            {
                $data = Dejavu::getObject('Data',(int)$dataid);
                echo DataVis::makeForm($data)."\n";
            }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="message-bottom col-sm-6 text-left">
                 <?php echo $chatuser->sname.' '.$chatuser->fname; ?>
        </div>
        <div class="message-bottom col-sm-6 text-right">
             <?php include_once APP.'/algorythm.php';
             echo timeFormating($message->date); ?>
        </div>
    </div>
</div>
