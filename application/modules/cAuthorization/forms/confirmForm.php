<div class="row">
    <div class="col-sm-4 col-sm-offset-4"></div>
        <form action='' method='post'>
            <div class="form-group <?php if (!$validConfirm['password']): ?>has-error<?php endif; ?>">
                <label>Новый пароль</label>
                <input class="form-control" name='password' type='password' required pattern='^[a-zA-Z0-9_]{6,20}$' required value=''>
                <?php if (!$validConfirm['password']): ?><p class="help-block">Пароль должен состоять из латин...</p><?php endif; ?>
            </div>
            <div class="form-group <?php if (!$validConfirm['passwordRepeated']): ?>has-error<?php endif; ?>">
                <label>Повторите пароль</label>
                <input class="form-control" name='passwordRepeated' type='password' required pattern='^[a-zA-Z0-9_]{6,20}$' required value=''>
                <?php if (!$validConfirm['passwordRepeated']): ?><p class="help-block">Неверно повторён пароль</p><?php endif; ?>
            </div>
            <div class="form-group pull-right">
                <input name='submitConfirm' type='submit' value="Подтвердить">
            </div>
        </form>
    </div>
</div>