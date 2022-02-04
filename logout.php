<?php
declare(strict_types=1);
error_reporting(-1);

require __DIR__ . '/config/config.php';

if (!empty($_GET['logout']) && ($_GET['logout'] == 1)) {
    $_SESSION['user'] = [];
    unset($_SESSION['user']);
    session_destroy();

    header("Location: /");
    die;
}

