<table>
    <?php
        foreach ($stages as $stage)
        {
            if ($stage['activity'])
                echo '<tr><td><a href="'.URI.'/'.$stage['id'].'">'.$stage['name'].'</a></td><td>'.$stage['time'].'%</td></tr>';
            else
                echo '<tr><td>'.$stage['name'].'</td><td>'.$stage['time'].'%</td></tr>';
        }
        echo '<tr><td><a href="'.URI.'/../">Назад</a></td></tr>';
    ?>
</table>

