<?php
require_once('./helpers.php');
$db = require_once('config/db.php');

$con = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database'], $db['port']);
if (!$con) {
    echo "Cannot connect to database";
    die();
}

mysqli_set_charset($con, "utf8");

$is_auth = rand(0, 1);
