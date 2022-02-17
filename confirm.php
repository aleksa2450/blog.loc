<?php
declare(strict_types=1);
error_reporting(-1);

require __DIR__ . '/config/config.php';

if(isset($_GET['hash']) && !empty($_GET['hash'])) {
    $hash = strip_tags(htmlspecialchars(trim($_GET['hash'])));
    $query = "UPDATE users SET 
    `status` = '2',
    `hash` = ''
    WHERE `hash`='{$hash}'";
    $dbh->query($query);
    if ($dbh->affected_rows) {
        $_SESSION['success'] = "Подветрждение адреса электронной почты прошло успешно";
        header("Location: /");
        die;
    } else {
        $_SESSION['errors'] = "Ошибка подтверждения";
        header("Location: /");
        die;
    }

} else {
    http_response_code(404);
    header("Location: 404.php");
    die;
}