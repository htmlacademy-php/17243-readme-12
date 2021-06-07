<?php
function get_posts($con): ?array
{
    $sql = '
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
            LEFT JOIN content_types AS c1 ON p.content_types_id = c1.id
            LEFT JOIN comments AS c2 ON p.id = c2.posts_id
            LEFT JOIN likes AS l ON p.id = l.posts_id
        GROUP BY p.id;
    ';

    $result = mysqli_query($con, $sql);

    if ($result) {
        $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $posts;
    }

    return null;
}

function get_posts_by_id($con, ?int $id): ?array
{
    $subquery = is_null($id) ? "p.content_types_id = c.id" : "p.id = $id";

    $sql = "
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

    $result = mysqli_query($con, $sql);

    if ($result) {
        $posts = array_map(function ($post) {
            ['classname' => $type] = $post;

            return array_merge(
                ['type' => "post-{$type}"],
                array_slice($post, 1)
            );
        }, mysqli_fetch_all($result, MYSQLI_ASSOC));

        return $posts;
    }

    return null;
}
