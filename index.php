<?php
require_once('./helpers.php');
require_once('./config/init.php');
require_once('./models/posts.php');
require_once('./models/content_types.php');

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$content_types = get_content_types($con) ?? [];
$posts = get_posts_by_id($con, $id) ?? [];

$head = include_template('partials/head.php', ['title' => 'readme: популярное']);
$symbols = include_template('partials/symbols.php');
$page_header = include_template('partials/header.php', ['username' => 'Вася Попкин']);
$page_content = include_template('partials/index/main.php', ['posts' => $posts, 'content_types' => $content_types, 'params' => ['content_type_id' => $id]]);
$page_footer = include_template('partials/footer.php');
$layout_content = include_template('partials/index/layout.php', [
    'head' => $head,
    'symbols' => $symbols,
    'page_header' => $page_header,
    'page_content' => $page_content,
    'page_footer' => $page_footer,
]);

print($layout_content);
