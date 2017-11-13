<div class="row">
    <div class="col-sm-4 col-sm-offset-4">
        <form method="post">
            <div class="form-group">
                <label for="areaid">Выберите регион</label>
                <select class="form-control" name="areaid" required>
                    <option value="" selected disabled></option>
                    <?php
                        foreach ($areas as $area)
                        {
                            echo '<option value="'.$area['areaid'].'">'.$area['name'].'</option>';
                        }
                    ?>
                </select>
            </div>
            <div class="form-group" id="city_choise" hidden>
                <label for="areaid">Выберите город</label>
                <?php
                    foreach($cities as $areaid => $citiess)
                    {
                        echo '<div>';
                        echo '<select class="cityid form-control" id="area' . $areaid . '" name="cityid_' . $areaid . '">';
                        echo '<option value="" selected disabled></option>';
                        foreach ($citiess as $city)
                        {
                            echo '<option value="'.$city['cityid'].'">'.$city['name'].'</option>';
                        }
                        echo '</select>';
                        echo '</div>';
                    }
                ?>
            </div>
            <div class="form-group pull-right" id="sup" hidden>
                <input class="form-control btn btn-success" type="submit" name="city" value="Создать">
            </div>
        </form>
    </div>
</div>
<script>
    $('select[name="areaid"]').change(function () {
        $('div[id="city_choise"]').show();
        $('div[id="sup"]').show();
        $('.cityid').hide();
        $('#area' + $('select[name="areaid"]').val()).show();
    });
</script>