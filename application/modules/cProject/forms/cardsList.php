<table>
    <?php
        foreach ($cards as $card)
        {
            echo '<tr><td><a href="'.URI.'/'.$card['id'].'">'.$card['name'].'</a></td></tr>';
        }
        echo '<tr><td><a href="'.URI.'/../">Назад</a></td></tr>';
    ?>
</table>

