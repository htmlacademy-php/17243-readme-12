<?php
require_once('./helpers.php');
require_once('./config/init.php');
require_once('./const.php');
require_once('./models/posts.php');
require_once('./models/comments.php');
require_once('./models/hashtags.php');
require_once('./models/users.php');

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if (!$id) {
    http_response_code(404);
}

$posts = get_posts($con, $SEARCH_TYPES, 'random_words');

if ($posts) {
    $posts_ids = array_column($posts, 'id');

    if (!in_array($id, $posts_ids)) {
        http_response_code(404);
        die();
    }
}

$hashtags = get_hashtags_by_id($con, $id) ?? [];
$comments = get_post_comments_by_id($con, $id);
$user_details = get_user_details_by_post_id($con, $id) ?? [];

$head = include_template('partials/head.php', ['title' => 'readme: публикация']);
$symbols = include_template('partials/symbols.php');
$page_header = include_template('partials/header.php');
$page_content = include_template('partials/post_details/main.php', [
    'post' => array_merge(
        [],
        ...array_filter(
            $posts,
            function ($post) use ($id) {
                return $post['id'] == $id;
            }
        )
    ),
    'hashtags' => $hashtags,
    'comments' => $comments,
    'user_details' => $user_details
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
