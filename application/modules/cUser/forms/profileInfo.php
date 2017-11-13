

<div class="container">
    <div class="row">
        <div class="col-md-3">
            <?php echo '<img src="/avatar/' . $profileInfo['avatarid'] . '" class="img img-responsive">' ?>
            <?php if ($_SESSION['user']['userid'] == $profileInfo['userid']): ?>
                <?php echo '<form enctype="multipart/form-data" method="post" target="/saveavatar/' . $profileInfo['avatarid'] . '">'; ?>
                <div class="form-group">
                    <label class="btn btn-default btn-file btn-sm" style="display: block">
                        Сменить фото
                        <input id="new-avatar" class="btn btn-default form-controll" type="file" name="avatar" style="display: none">
                    </label>
                </div>
                <div class="form-group">
                    <input id="submit-new-avatar" class="btn btn-success form-controll" type="submit" name="avatarSave" value="Загрузить" style="display: none">
                </div>
                </form>
            <?php endif; ?>
        </div>

        <div class="col-md-9">
            <div id="profileData">
                <button id="changeBtn" class="btn btn-secondary"><span class="glyphicon glyphicon-cog"></span></button>
                <h2 id="fio"><?php echo $profileInfo['sname'] . ' ' . $profileInfo['fname'] . ' ' . $profileInfo['tname']; ?></h2>
                <p class="text-info"><span class="glyphicon glyphicon-user"></span><?php echo $profileInfo['type']; ?></p>
                <p id="pnum"><span class="glyphicon glyphicon-phone-alt"></span> <?php echo $profileInfo['pnum']; ?></p>
                <p id="mail"><span class="glyphicon glyphicon-envelope"></span> <?php echo $profileInfo['mail']; ?></p>
            </div>
            
            <?php if ($_SESSION['user']['userid'] == $profileInfo['userid']): ?>
            <form id="profileForm" target="" enctype="multipart/form-data" method="post" hidden>
                <div class="form-group">
                    <label for="fname">Фамилия</label>
                    <input class="form-control" name="sname" type="text" value="<?php echo $profileInfo['sname']; ?>">
                </div>
                <div class="form-group">
                    <label for="fname">Имя</label>
                    <input class="form-control" name="fname" type="text" value="<?php echo $profileInfo['fname']; ?>">
                </div>
                <div class="form-group">
                    <label for="fname">Отчество</label>
                    <input class="form-control" name="tname" type="text" value="<?php echo $profileInfo['tname']; ?>">
                </div>
                <div class="form-group">
                    <label for="fname">Тел.</label>
                    <input class="form-control" name="pnum" type="text" value="<?php echo $profileInfo['pnum']; ?>">
                </div>
                <div class="form-group">
                    <label for="fname">E-mail</label>
                    <input class="form-control" name="mail" type="text" value="<?php echo $profileInfo['mail']; ?>">
                </div>
                <div class="form-group">
                    <label for="fname">Новый пароль</label>
                    <input class="form-control" name="changedPassword" type="password" value="">
                </div>
                <div class="form-group">
                    <label for="fname">Повторите пароль</label>
                    <input class="form-control" name="confirmedPassword" type="password" value="">
                </div>
                <div class="form-group pull-right">
                    <input class="form-control btn btn-primary" name="submitProfile" type="submit" value="Подтвердить">
                </div>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    <?php if ($_SESSION['user']['userid'] == $profileInfo['userid']): ?>
        $('#new-avatar').change(function () {
            $('#submit-new-avatar').show();
        });
        
        $('#changeBtn').click(function (event) {
            $('#profileData').hide();
            $('#profileForm').show();
        });
    <?php endif; ?>
</script>