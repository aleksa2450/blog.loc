<?php
session_start();

$dbh = new mysqli('localhost', 'root', '', 'legacy-blog');

if ($dbh->connect_errno) {
    die ("Ошибка подключения к БД" . $dbh->connect_error);
}
$dbh->set_charset('utf-8');

function dump($data)
{
    echo '<pre>'; var_dump($data); echo '</pre>';
}