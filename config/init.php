<?php
require_once('./helpers.php');
$db = require_once('config/db.php');

$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database'], $db['port']);
if (!$link) {
    echo "Cannot connect to database";
    die();
}

mysqli_set_charset($link, "utf8");

$is_auth = rand(0, 1);
