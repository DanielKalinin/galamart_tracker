<div class="row">
    <div class="col-sm-4 col-sm-offset-4">
        <form action='' method='post'>
           <div class="form-group <?php if (!$validPnum): ?>has-error<?php endif; ?>">
                <label for="pnum">Тел.</label>
                <div class="input-group">
                    <div class="input-group-addon">+</div>
                    <input class="form-control" name='pnum' type='text' required>
                </div>
                <?php if (!$validPnum): ?>
                <p class="help-block">Неправильный телефон</p>
                <?php else: ?>
                <p class="help-block">Введите номер телефона для восстановления пароля</p>
                <?php endif; ?>
            </div>
            <div class="form-group pull-right">
                <input class="form-control btn btn-success" name='submitRepair' type='submit' value="Далее">
            </div>
        </form>
    </div>
</div>