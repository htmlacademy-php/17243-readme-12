<?php
require_once('./helpers.php');
$db = require_once('config/db.php');

$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database'], $db['port']);
if (!$link) {
    echo "Cannot connect to database";
    die();
}

mysqli_set_charset($link, "utf8");

$content_types = [];
$posts = [];
$content_types_query = 'SELECT * FROM content_types';
$posts_query = '
SELECT
    classname,
    title,
    body,
    login AS username,
    avatar_path AS userpic
FROM
    posts AS p
    INNER JOIN users AS u ON p.users_id = u.id
    INNER JOIN content_types AS c ON c.id = p.content_types_id
ORDER BY
    views_count DESC
';

/* $content_types  */
$result = mysqli_query($link, $content_types_query);

if ($result) {
    $content_types = mysqli_fetch_all($result, MYSQLI_ASSOC);
};

/* $posts  */
$result = mysqli_query($link, $posts_query);

if ($result) {
    $posts = array_map(function ($post) {
        ['classname' => $type] = $post;

        return array_merge(
            ['type' => "post-{$type}"],
            array_slice($post, 1)
        );
    }, mysqli_fetch_all($result, MYSQLI_ASSOC));
}

$is_auth = rand(0, 1);
$user_name = 'Андрей';

function truncate($text, $threshold = 300)
{
    $words = preg_split("/\s/", html_entity_decode($text));

    $result_str = implode(" ", array_reduce($words, function ($acc, $word) use ($threshold) {
        if (strlen(implode(" ", $acc)) < $threshold) {
            $acc[] = $word;
        }

        return $acc;
    }, []));

    return strlen($result_str) < $threshold ? [$text, false] : [$result_str, true];
}

$page_content = include_template('main.php', ['posts' => $posts, 'content_types' => $content_types]);
$layout_content = include_template('layout.php', [
    'page_content' => $page_content,
    'title' => 'readme: популярное',
    'username' => $user_name,
    'is_auth' => $is_auth,
]);

print($layout_content);
