<?php
require_once('./helpers.php');
require_once('./config/init.php');
require_once('./const.php');
require_once('./models/posts.php');
require_once('./models/users.php');
require_once('./models/content_types.php');

if (!isset($_SESSION['user'])) {
    header("Location: /index.php");
    die();
}

$user_id = $_SESSION['user']['id'];
$active_category_id = filter_input(INPUT_GET, 'content_type_id', FILTER_SANITIZE_NUMBER_INT);
$post_id = filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_NUMBER_INT);
$content_types = get_content_types($con) ?? [];
$posts = get_posts(
    $con,
    $SEARCH_TYPES,
    'subscriptions',
    $user_id,
    $active_category_id
) ?? [];
$user_details = [];
$reposts_count = 0;

if (!empty($posts)) {
    foreach ($posts as $post) {
        $user_details = get_user_by_id($con, $post['user_id']) ?? [];
    }
}

if ($post_id) {
    make_repost($con, intval($post_id), $user_id);
}

$head = include_template('partials/head.php', ['title' => 'readme: моя лента']);
$symbols = include_template('partials/symbols.php');
$page_header = include_template('partials/header.php');
$page_content = include_template('partials/feed/main.php', [
    'posts' => array_filter(
        array_map(
            function ($post) use ($con, $user_details) {
                $post['username'] = $user_details['login'] ?? null;
                $post['userpic'] =  $user_details['avatar_path'] ?? null;
                $post['reposts_count'] = get_reposts_count($con, $post['id']);

                return $post;
            },
            $posts
        )
    ),
    'content_types' => $content_types,
    'active_category_id' => isset($active_category_id) ? $active_category_id : 0,
]);
$page_footer = include_template('partials/feed/footer.php');
$layout_content = include_template('partials/layout.php', [
    'head' => $head,
    'symbols' => $symbols,
    'page_header' => $page_header,
    'page_content' => $page_content,
    'page_footer' => $page_footer,
]);

print($layout_content);
