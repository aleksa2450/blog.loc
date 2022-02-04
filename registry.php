<?php
declare(strict_types=1);
error_reporting(-1);

require __DIR__ . '/config/config.php';

if (!empty($_POST['mode']) && ($_POST['mode'] === 'add_user')){
    $data = [];
    $errors = [];


    foreach ($_POST as $key => $value) {

        $data[$key] = strip_tags(htmlspecialchars(trim($value)));
    }

    $user_name = $_POST['user_name'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;

    if(empty($user_name)) {
        $errors['user_name'] = 'Заполните поле имя.' . PHP_EOL;
    }

    if(empty($email)) {
        $errors['email'] = 'Заполнте поле email.' . PHP_EOL;
    }

    if(empty($password)) {
        $errors['password'] = 'Заполните поле пароль.' . PHP_EOL;
    }

    if(!empty($errors)) {
        $_SESSION['error'] = [
            'user_name' => $errors['user_name'] ?? null,
            'email' => $errors['email'] ?? null,
            'password' => $errors['password'] ?? null,
        ];
        $_SESSION['user_name'] = $user_name ?? null;
        $_SESSION['email'] = $email ?? null;
    } else {
        $sth = $dbh->query("SELECT `id` FROM `users` WHERE `email` = '{$email}'");
        $result = $sth->fetch_assoc();
        if ($result) {
            $_SESSION['error'] = [
                'email' => 'Этот email уже зарегистрирован',
            ];
            $_SESSION['user_name'] = $user_name ?? null;
            header("Location: registry.php");
            die;
        } else {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $sth = $dbh->query("INSERT INTO `users` SET `user_name` = '{$user_name}', `email` = '{$email}', `password` = '{$password}'");
            $_SESSION['success'] = 'Регистрация прошла успешно.';
        }
    }

    header("Location: /");
    die;
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Hello, world!</title>
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
                    <li class="nav-item">
                        <a class="nav-link" href="#">Вход</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="registry.php">Регистрация</a>
                    </li>


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
                <h1>Регистрация</h1>
                <form method="post">
                    <div class="form-group">
                        <label for="user_name">Имя</label>
                        <input type="text" name="user_name" value="<?php if (!empty($_SESSION['error'])): ?><?php echo $_SESSION['user_name']; ?><?php endif; ?>" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                        <small id="userNameHelp" class="form-text text-muted">
                                <?php if (!empty($_SESSION['error']['user_name'])): ?>
                                    <?php echo $_SESSION['error']['user_name']; ?>
                                <?php endif;?>
                        </small>
                    </div>

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
                    </div>
                    <?php if (!empty($_SESSION['error'])): ?>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <input type="hidden" name="mode" value="add_user">
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

