<?php
require_once('./config/init.php');

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$content_types = [];
$posts = [];
$subquery = is_null($id) ? 'p.content_types_id = c.id' : 'p.content_types_id = ' . intval($id);
$content_types_query = 'SELECT * FROM content_types';
$posts_query = "
SELECT
    c.classname,
    p.id,
    p.title,
    p.body,
    u.login AS username,
    u.avatar_path AS userpic
FROM
    posts AS p
    INNER JOIN users AS u ON p.users_id = u.id
    INNER JOIN content_types AS c ON c.id = p.content_types_id
WHERE $subquery
ORDER BY
    views_count DESC
";

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

$head = include_template('partials/head.php', ['title' => 'readme: популярное']);
$symbols = include_template('partials/symbols.php');
$page_header = include_template('partials/header.php', ['is_auth' => $is_auth]);
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
