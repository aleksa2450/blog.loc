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

    $user_name = $data['user_name'];
    $email = $data['email'];
    $password = $data['password'];

    if (empty($user_name)) {
        $errors['user_name'] = 'Заполните поле имя.' . PHP_EOL;
    } else {
        if (!preg_match("/^[a-z0-9-_]{3,15}$/i", $user_name)){
            $errors['user_name'] = 'Вы ввели недопустимые символы';
        }
        $len = strlen($user_name);
        if ($len < 3) {
            $errors['user_name'] = 'Длина логина не может быть меньше 3х символов!' . PHP_EOL;
        }

    } //  проверка на допустимые символы
    //проверка на длину не менее 3 символов

    if (empty($email)) {
        $errors['email'] = 'Заполнте поле email.' . PHP_EOL;
    } else {

        if (!preg_match("/^(?:[a-z0-9]+(?:[-_.]?[a-z0-9]+)?@[a-z0-9_.-]
+(?:\.?[a-z0-9]+)?\.[a-z]{2,5})$/i", $email)) {
            $errors['email'] = 'Неккоректный ввод email-адреса. Попробуйте снова' . PHP_EOL;
        }
    }
    // проверка на допустимый email

    if (empty($password)) {
        $errors['password'] = 'Заполните поле пароль.' . PHP_EOL;
    } else {
        if (!preg_match("^\S*(?=\S{5,20})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$", $password)) {
            $errors['password'] = 'Пароль введён неккоректно. Повторите попытку' . PHP_EOL;
        }

    }
    //Проверка на цифры и символы
    //Проверка на хотя бы 1 символ заглавный
    // Проверка на длину не менее 5 символов
                                                                                                                                                                        
    if (!empty($errors)) {
        $_SESSION['error'] = [
            'user_name' => $errors['user_name'] ?? null,
            'email' => $errors['email'] ?? null,
            'password' => $errors['password'] ?? null,
        ];
        $_SESSION['user_name'] = $user_name ?? null;
        $_SESSION['email'] = $email ?? null;
        header("Location: registry.php");
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

                    <?php if (!empty($_SESSION['user'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Выход</a>
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
                        <input type="text" name="email" value="
                        <?php if (!empty($_SESSION['error'])): ?>
                            <?php echo $_SESSION['email']; ?>
                        <?php endif; ?>"
                               class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
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

