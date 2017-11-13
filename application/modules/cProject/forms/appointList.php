<form method="post">
    <?php
        if (empty($users))
            echo "На данный момент нет подходящих пользователей";
        else
        {
            echo '<select name="userid" required>';

            foreach ($users as $user)
            {
                echo '<option value='.$user['userid'].'>'.$user['name'].' ('.$user['projects'].')</option>';
            }

            echo '</select>';
            echo '<input type="submit" name="user" value="Выбрать">';
        }
    ?>
</form>



