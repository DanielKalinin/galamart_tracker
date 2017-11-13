<div class="row">
    <div class="col-sm-4 col-sm-offset-4">
        <?php if (!$isValidAuth): ?>
        <div class="alert alert-danger">Неправильный логин или пароль</div>
        <?php endif; ?>
        
        <form action="" method="post">
            <div class="form-group">
                <label  for="pnum">Тел.</label>
                <div class="input-group">
                    <div class="input-group-addon">+</div>
                    <input class="form-control" name="pnum" type="tel" required value="<?php echo $_POST['pnum'] ?>">
                </div>
            </div>

            <div class="form-group">
                <label align="right">Пароль</label>
                <input class="form-control" name="password" type="password" required value="">
            </div>

            <div class="form-group pull-right">
                <input class="form-control btn btn-primary" name="submitAuth" type="submit" value="Войти" required>
            </div>
        <a class="btn btn-default" href='/authorization/repair'>Забыли пароль?</a>
    </div>
</div>

