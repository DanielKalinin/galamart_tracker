<div class="row">
    <div class="col-sm-4 col-sm-offset-4">
<form action='' method='post'>
    <?php if (empty($_POST['sname'])): ?>
    <div class="form-group">
    <?php elseif ($validRegInputs['sname']): ?>
    <div class="form-group has-success">
    <?php else: ?>
    <div class="form-group has-error">
    <?php endif; ?>
        <label for="sname">Фамилия</label>
        <?php echo "<input name='sname' class='form-control' type='text' pattern='^[А-ЯЁ][а-яё]+$' "
        . "required value='{$_POST['sname']}'></input>"; ?>
        <?php if (!$validRegInputs['sname']): ?>
        <p class="help-block">Фамилия должно содержать только кириллицу и начинаться с заглавной буквы</p>
        <?php endif; ?>
    </div>
    <?php if (empty($_POST['fname'])): ?>
    <div class="form-group">
    <?php elseif ($validRegInputs['fname']): ?>
    <div class="form-group has-success">
    <?php else: ?>
    <div class="form-group has-error">
    <?php endif; ?>
        <label for="fname">Имя</label>
        <?php echo "<input name='fname' class='form-control' type='text' pattern='^[А-ЯЁ][а-яё]+$' "
        . "required value='{$_POST['fname']}'></input>"; ?>
        <?php if (!$validRegInputs['fname']): ?>
        <p class="help-block">Имя должно содержать только кириллицу и начинаться с заглавной буквы</p>
        <?php endif; ?>
    </div>
    <?php if (empty($_POST['tname'])): ?>
    <div class="form-group">
    <?php elseif ($validRegInputs['tname']): ?>
    <div class="form-group has-success">
    <?php else: ?>
    <div class="form-group has-error">
    <?php endif; ?>
        <label for="tname">Отчество</label>
        <?php echo "<input name='tname' class='form-control' type='text' pattern='^[А-ЯЁ][а-яё]+$' "
        . "required value='{$_POST['tname']}'></input>"; ?>
        <?php if (!$validRegInputs['tname']): ?>
        <p class="help-block">Отчество должно содержать только кириллицу и начинаться с заглавной буквы</p><?php endif; ?>
    </div>
    <?php if (empty($_POST['mail'])): ?>
    <div class="form-group">
    <?php elseif ($validRegInputs['mail'] && $validRegInputs['unique']): ?>
    <div class="form-group has-success">
    <?php else: ?>
    <div class="form-group has-error">
    <?php endif; ?>
        <label for="mail">E-mail</label>
        <?php echo "<input name='mail' class='form-control' type='text' pattern='^[a-zA-Z0-9_\-.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-.]+' "
        . "required value='{$_POST['mail']}'></input>"; ?>
        <?php if (!$validRegInputs['mail'] || !$validRegInputs['unique']): ?>
        <p class="help-block">E-mail некорректен или уже зарегистрирован</p><?php endif; ?>
    </div>
    <?php if (empty($_POST['pnum'])): ?>
    <div class="form-group">
    <?php elseif ($validRegInputs['pnum'] && $validRegInputs['unique']): ?>
    <div class="form-group has-success">
    <?php else: ?>
    <div class="form-group has-error">
    <?php endif; ?>
        <label for="pnum">Тел.</label>
        <div class="input-group">
            <div class="input-group-addon">+</div>
        <?php echo "<input name='pnum' class='form-control' type='tel' pattern='^\d{11}$' "
        . "required value='{$_POST['pnum']}'></input>"; ?>
        </div>
        <?php if (!$validRegInputs['pnum'] || !$validRegInputs['unique']): ?>
        <p class="help-block">Номер некорректен или уже зарегистрирован</p><?php endif; ?>
    </div>
    <?php if ($validRegInputs['password']): ?>
    <div class="form-group">
    <?php else: ?>
    <div class="form-group has-error">
    <?php endif; ?>
        <label for="password">Пароль</label>
        <?php echo "<input name='password' class='form-control' type='password' pattern='^[a-zA-Z0-9_]{6,20}$' "
        . "required value=''></input>"; ?>
        <?php if (!$validRegInputs['password']): ?>
        <p class="help-block">Некорректный пароль</p><?php endif; ?>
    </div>
    <?php if ($validRegInputs['passwordRepeated']): ?>
    <div class="form-group">
    <?php else: ?>
    <div class="form-group has-error">
    <?php endif; ?>
        <label for="passwordRepeated">Повторите пароль</label>
            <?php echo "<input name='passwordRepeated' class='form-control' type='password' "
            . "required value=''></input>"; ?>
        <?php if (!$validRegInputs['passwordRepeated']): ?>
        <p class="help-block">Неверный повтор пароля</p><?php endif; ?>
    </div>

    <?php if (!empty($_POST['type'])): ?>
    <div class="form-group has-warning">
    <?php else: ?>
    <div class="form-group">
    <?php endif; ?>
        <label for="type">Кто вы</label>
        <td align='right'>
            <select name='type' class='form-control'>
            <option value="" selected disabled></option>
            <option value='franchise'>Франчайзи</option>
            <option value='coordinator'>Координатор</option>
            <option value='project_manager'>Руководитель проекта</option>
            <option value='curator'>Куратор</option>
            <option value='technical_support'>Техническая поддержка</option>
            <option value='price_manager'>Менеджер ценообразования</option>
            <option value='advert_manager'>Менеджер маркетинга</option>
            <option value='logist'>Логист</option>
            <option value='merchandiser'>Мерчендайзер</option>
            <option value='technologist'>Технолог</option>
            <option value='study_center'>Центр обучения</option>
            <option value='security_center'>Центр защиты</option>
            <option value='development_manager'>Менеджер разработки</option>
            <option value='selection_center'>Отдел подбора</option>
            <option value='designer'>Дизайнер</option>
            <option value='arend_center'>Центр аренды</option>
            <option value='visiting_coach'>Выездной тренер</option>
            </select>
        </td>
    </div>
    <?php if ($hasRegCookie): ?>
    <a class="btn btn-info pull-right" href='/registration/confirm'>Ввести код.</a>
    <?php else: ?>
    <div class="form-group pull-right">
        <input class="btn btn-success" name='submitReg' type='submit' value='Далее'>
    </div>
    <?php endif; ?>
</form>
    </div>
</div>
        
