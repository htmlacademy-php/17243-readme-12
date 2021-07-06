<?php
require_once('./helpers.php');
require_once('./config/init.php');
require_once('./models/posts.php');
require_once('./models/content_types.php');
require_once('./services/pagination.php');

if (!isset($_SESSION['user'])) {
    header("Location: /index.php");
    die();
}

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$content_types = get_content_types($con) ?? [];
$cur_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$page_items = 6;
[$pages_count, $offset] = paginate($con, $cur_page, $page_items);
$posts = get_popular_posts($con, $id, $page_items, $offset) ?? [];

$head = include_template('partials/head.php', ['title' => 'readme: популярное']);
$symbols = include_template('partials/symbols.php');
$page_header = include_template('partials/header.php');
$page_content = include_template('partials/popular/main.php', [
    'posts' => $posts,
    'content_types' => $content_types,
    'params' => [
        'content_type_id' => $id,
        'cur_page' => $cur_page,
        'pages_count' => $pages_count
    ]
]);
$page_footer = include_template('partials/footer.php');
$layout_content = include_template('partials/popular/layout.php', [
    'head' => $head,
    'symbols' => $symbols,
    'page_header' => $page_header,
    'page_content' => $page_content,
    'page_footer' => $page_footer,
]);

print($layout_content);
