<?php
declare(strict_types=1);
error_reporting(-1);

require __DIR__ . '/config/config.php';

if (!empty($_POST['mode']) && ($_POST['mode'] ==='login_user')) {
    $errors = [];
    $data = [];

    foreach ($_POST as $key => $value) {
        $data[$key] = strip_tags(htmlspecialchars(trim($value)));
    }

    $userEmail = $data['email'];
    $password = $data['password'];

    if (empty($userEmail)) {
        $errors['email'] = 'Заполните поле email.';
    } elseif (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Неккоректный email-адрес.';
    }

    if (empty($password)) {
        $errors['password'] = 'Заполните поле пароль.';
    }

    if (!empty($errors)) {
        $_SESSION['error'] = [
            'email' => $errors['email'] ?? null,
            'password' => $errors['password'] ?? null,
        ];

        $_SESSION['email'] = $user_email ?? null;

        header("Location: login.php");
        die;
    } else {
        $sth = $dbh->query("SELECT *  FROM `users` WHERE `email` = '{$userEmail}'");

        $user = $sth->fetch_assoc();
        if (!$user) {
            $_SESSION['error_aut'] = 'Email/Пароль введены неверно.';
            $_SESSION['email'] = $user_email ?? null;

            header("Location: login.php");
            die;
        } else {
            if (!password_verify($password, $user['password'])) {
                $_SESSION['error_aut'] = 'Email/Пароль введены неверно.';
                $_SESSION['email'] = $user_email ?? null;

                header("Location: login.php");
                die;
            } else {
                $data = [];
                foreach ($user as $key => $value) {
                    if ('password' == $key) {
                        continue;
                    }
                    $data[$key] = $value;
                }

                $_SESSION['user'] = $data;
                $_SESSION['success'] = 'Вы успешно авторизованы';

                header("Location: /");
                die;
            }
        }
    }
}
$title = 'Вход';
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
                            <a class="nav-link" href="#">Выход</a>
                        </li>
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

        <div class="row">
            <div class="col-md-12">
                <h1>Авторизация</h1>

                <?php if(!empty($_SESSION['error_aut'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $_SESSION['error_aut']; ?>
                    </div>
                    <?php unset($_SESSION['error_aut']); ?>
                <?php endif; ?>

                <form method="post">

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" name="email" value="<?php if (!empty($_SESSION['error'])): ?><?php echo $_SESSION['email']; ?><?php endif; ?>" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                        <small id="emailHelp" class="form-text text-muted">
                            <?php if (!empty($_SESSION['error']['email'])): ?>
                                <?php echo $_SESSION['error']['email']; ?>
                            <?php endif;?>
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="password">Пароль</label>
                        <input type="text" name="password" value="" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                        <small id="passwordHelp" class="form-text text-muted">
                            <?php if (!empty($_SESSION['error']['password'])): ?>
                                <?php echo $_SESSION['error']['password']; ?>
                            <?php endif;?>
                        </small>
                        </small>
                    </div>
                    <?php if (!empty($_SESSION['error'])): ?>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>
                    <input type="hidden" name="mode" value="login_user">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </form>

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

