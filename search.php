<?php
require_once('./helpers.php');
require_once('./config/init.php');
require_once('./const.php');
require_once('./models/posts.php');
require_once('./models/users.php');

$posts = get_posts(
    $con,
    $SEARCH_TYPES,
    $_GET['type'],
    $_GET['q']
) ?? [];

if (count($posts)) {
    $posts = array_map(function ($post) use ($con) {
        $user_details = isset($post['id']) ? get_user_details_by_post_id($con, $post['id']) : [];
        return array_merge(
            $post,
            array_filter(
                [
                    'username' => $user_details['username'] ?? null,
                    'userpic' => $user_details['userpic'] ?? null
                ]
            )
        );
    }, $posts);
}

$head = include_template('partials/head.php', ['title' => 'readme: публикация']);
$symbols = include_template('partials/symbols.php');
$page_header = include_template('partials/header.php');
$page_content = include_template('partials/search/main.php', [
    'posts' => $posts,
    'search' => $_GET['q']
]);
$page_footer = include_template('partials/footer.php');
$layout_content = include_template('partials/layout.php', [
    'head' => $head,
    'symbols' => $symbols,
    'page_header' => $page_header,
    'page_content' => $page_content,
    'page_footer' => $page_footer
]);

print($layout_content);
