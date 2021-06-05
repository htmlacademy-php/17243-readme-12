<?php
require_once('./config/init.php');

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if (!$id) {
    http_response_code(404);
}

$posts_query = '
    SELECT
        p.id,
        p.original_post_id,
        p.users_id,
        p.title,
        p.body,
        p.views_count,
        (SELECT COUNT(`id`) FROM comments c WHERE c.posts_id = p.id) as comments_count,
        (SELECT COUNT(`id`) FROM likes l WHERE l.posts_id = p.id) as likes_count,
        c1.classname AS type
    FROM
        posts AS p
        INNER JOIN content_types AS c1 ON p.content_types_id = c1.id
        INNER JOIN comments AS c2 ON p.id = c2.posts_id
        LEFT JOIN likes AS l ON p.id = l.posts_id
    GROUP BY p.id;
';
$hashtags_query = "
    SELECT
        name
    FROM
        hashtags
        INNER JOIN posts_has_hashtags  ON hashtags.id = posts_has_hashtags.hashtags_id
    WHERE
        posts_has_hashtags.posts_id = $id;
";

$comments_query = "
    SELECT
        c1.dt_add,
        c1.text,
        c2.login AS username,
        c2.avatar_path AS userpic
    FROM
        comments AS c1
        INNER JOIN posts AS p ON p.id = c1.posts_id
        AND p.id = $id
        INNER JOIN users AS c2 ON c2.id = c1.users_id;
";

$users_query = "
    SELECT
        u.dt_add,
        u.login AS username,
        u.avatar_path AS userpic,
        (
            SELECT
                COUNT(`id`)
            FROM
                subscriptions s
            WHERE
                s.users_id = u.id
        ) AS subscribers_count,
        (
            SELECT
                COUNT(`id`)
            FROM
                posts p
            WHERE
                p.users_id = u.id
        ) AS posts_count
    FROM
        users AS u
        INNER JOIN posts AS p ON p.users_id = u.id
        AND p.id = $id
";

$result = mysqli_query($link, $posts_query);

if ($result) {
    $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $posts_ids = array_column($posts, 'id');

    if (!in_array($id, $posts_ids)) {
        http_response_code(404);
    }
}

$result = mysqli_query($link, $hashtags_query);

if ($result) {
    $hashtags = mysqli_fetch_assoc($result);
}

$result = mysqli_query($link, $comments_query);

if ($result) {
    $comments = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

$result = mysqli_query($link, $users_query);

if ($result) {
    $user_details = mysqli_fetch_assoc($result);
}

$head = include_template('partials/head.php', ['title' => 'readme: публикация']);
$symbols = include_template('partials/symbols.php');
$page_header = include_template('partials/header.php', ['is_auth' => $is_auth]);
$page_content = include_template('partials/post/main.php', [
    'post' => array_merge([], ...array_filter($posts, fn ($post) => $post['id'] === $id)),
    'hashtags' => $hashtags,
    'comments' => $comments,
    'user_details' => $user_details,
]);
$page_footer = include_template('partials/footer.php');
$layout_content = include_template('partials/post/layout.php', [
    'head' => $head,
    'symbols' => $symbols,
    'page_header' => $page_header,
    'page_content' => $page_content,
    'page_footer' => $page_footer
]);

print($layout_content);
