<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <?php
        eval('?>' . $pageHeader);
        ?>
        <style>
            .alert-pink {
                color: white;
                background-color: #a468d5;
                border-color: #a064d1;
            };
        </style>
    </head>
    <body>
        <header class="container-fluid">

            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="/">Franchise</a>
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-main-zone">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div class="navbar-collapse collapse" id="navbar-main-zone">
                        <div class="visible-xs">
                            <ul class="nav navbar-nav">
                            <?php if (isset($_SESSION['user'])): ?>
                                <li><a href='/user/<?php echo $_SESSION['user']['userid']."'>";
                                    echo $_SESSION['user']['fname'] . ' ' . $_SESSION['user']['sname']; ?>
                                       </a></li>
                                <li><a href="/projects"><span class="glyphicon glyphicon-blackboard"> Проекты</a></li>
                                <li><a href="/authorization/exit"><span class="glyphicon glyphicon-log-out"> Выйти</a></li>
                            <?php else: ?>
                                <li><a href="/authorization">Войти</a></li>
                                <li><a href="/registration">Регистрация</a></li>
                            <?php endif; ?>
                            </ul>
                        </div>
                        <div class="hidden-xs">
                            <?php if (isset($_SESSION['user'])): ?>
                                <div class="nav navbar-nav navbar-left">
                                    <li><a href='/user/<?php echo $_SESSION['user']['userid']."'>";
                                    echo 'Личный кабинет'; ?></a></li>
                                    <li><a href="/projects"><span class="glyphicon glyphicon-blackboard"> Проекты</span></a></li>
                                    <!--<a class="btn btn-primary navbar-btn"><span class="glyphicon glyphicon-cog"></span></a>-->
                                </div>
                                <div class="btn-group navbar-nav navbar-right">
                                    <a class="btn btn-default navbar-btn" href="/authorization/exit"><span class="glyphicon glyphicon-log-out"></span></a>
                                </div>
                            <?php else: ?>
                                <div class="navbar-nav navbar-right">
                                    <a class="btn btn-primary navbar-btn" href="/authorization">Войти</a>
                                    <a class="btn btn-default navbar-btn" href="/registration">Регистрация</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </nav>
        </header>

        <?php echo $breadcrumb; ?>

        <main class="container-fluid">
            <?php
            eval('?>' . $pageBody);
            ?>
        </main>
    </body>
</html>
