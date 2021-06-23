<?php
session_start();

require_once('./helpers.php');
$db_cfg = require_once('config/db.php');
$db_cfg = array_values($db_cfg);

$con = mysqli_connect(...$db_cfg);

if (!$con) {
    echo "Cannot connect to database";
    die();
}

mysqli_set_charset($con, "utf8");

/* $is_auth = rand(0, 1); */
