<?php
require_once('./helpers.php');
require_once('./config/init.php');

if (!isset($_SESSION['user'])) {
    header("Location: /index.php");
    die();
}

$head = include_template('partials/head.php', ['title' => 'readme: моя лента']);
$symbols = include_template('partials/symbols.php');
$page_header = include_template('partials/header.php');
$page_content = include_template('partials/feed/main.php');
$page_footer = include_template('partials/footer.php');
$layout_content = include_template('partials/feed/layout.php', [
    'head' => $head,
    'symbols' => $symbols,
    'page_header' => $page_header,
    'page_content' => $page_content,
    'page_footer' => $page_footer,
]);

print($layout_content);
