<?php
declare(strict_types=1);
error_reporting(-1);

require __DIR__ . '/config/config.php';




$title = 'Главная';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">;
    <link rel="stylesheet" href="assets/css/style.css">;
</head>
<body>

<div class="container">
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="/">DEMO-SITE</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="/">Главная <span class="sr-only">(current)</span></a>
                    </li>

                    <?php if (!empty($_SESSION['user'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php?logout=1">Выход</a>
                        </li>
                    <?php if ($_SESSION['user']['is_admin'] == 2): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Панель управления</a>
                            </li>
                    <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Личный кабинет</a>
                            </li>
                    <?php endif; ?>

                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Вход</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="registry.php">Регистрация</a>
                        </li>
                    <?php endif; ?>

                </ul>
                <form class="form-inline my-2 my-lg-0">
                    <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </form>
            </div>
        </nav>
    </header>

    <div class="wrapper mt-5">
        <?php if (!empty($_SESSION['success'])): ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-success" role="alert">
                        <?php echo $_SESSION['success']; ?>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['user'])):?>
            <?php dump($_SESSION['user']); ?>
        <?php endif;?>
        <div class="row">

            <?php if (!empty($_SESSION['errors'])): ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-success" role="alert">
                            <?php echo $_SESSION['errors']; ?>
                        </div>
                        <?php unset($_SESSION['errors']); ?>
                    </div>
                </div>
            <?php endif; ?>



            <?php if (!empty($_SESSION['success'])): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $_SESSION['success']; ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <div class="col-md-3">
                <div class="card" style="width: 18rem;">
                    <img src="..." class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card" style="width: 18rem;">
                    <img src="..." class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card" style="width: 18rem;">
                    <img src="..." class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr>
    <footer>
        <div class="row">
            <div class="col-md-12">
                <div class="text-center">
                    <p>© <?php echo date('Y')?> DEMO-SITE</p>
                </div>
            </div>
        </div>
    </footer>
</div>


<script src="assets/js/jquery-1.12.4.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>>
</body>
</html>
