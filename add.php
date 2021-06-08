<?php
require_once('./helpers.php');
require_once('./config/init.php');
require_once('./models/content_types.php');

$content_types = get_content_types($con) ?? [];

$head = include_template('partials/head.php', ['title' => 'readme: добавление публикации']);
$symbols = include_template('partials/symbols.php');
$page_header = include_template('partials/header.php', ['is_auth' => $is_auth, 'username' => 'Вася Попкин']);
$page_content = include_template('partials/adding_post/main.php', ['content_types' => $content_types]);
$page_footer = include_template('partials/footer.php');
$modal = include_template('partials/adding_post/modals/add.php');
$layout_content = include_template('partials/adding_post/layout.php', [
    'head' => $head,
    'symbols' => $symbols,
    'page_header' => $page_header,
    'page_content' => $page_content,
    'page_footer' => $page_footer,
    'modal' => $modal
]);

print($layout_content);
