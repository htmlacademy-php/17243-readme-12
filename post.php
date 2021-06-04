<?php
require_once('./config/init.php');

$head = include_template('head.php', ['title' => 'readme: публикация']);
$symbols = include_template('symbols.php');
$page_header = include_template('header.php', ['is_auth' => $is_auth]);
$page_content = include_template('partials/post/main.php');
$page_footer = include_template('footer.php');
$layout_content = include_template('partials/post/layout.php', [
    'head' => $head,
    'symbols' => $symbols,
    'page_header' => $page_header,
    'page_content' => $page_content,
    'page_footer' => $page_footer
]);

print($layout_content);
