<?php
function get_posts(mysqli $con, ?string $search = null, string $type = 'random'): ?array
{
    $fs_subquery = isset($search) ? '
        WHERE
            MATCH(title, body) AGAINST(?)
    ' : '';

    $posts_by_id_subquery = '
        WHERE
            p.id IN (
                SELECT
                    post_id
                FROM
                    posts_has_hashtags
                WHERE
                    hashtag_id = (
                        SELECT
                            id
                        FROM
                            hashtags
                        WHERE
                            name = ?
                    )
            )
    ';

    $subquery = $type === 'hashtag' ? $posts_by_id_subquery : $fs_subquery;

    $sorting_rule = $type === 'hashtag' ? 'ORDER BY p.dt_add DESC' : '';

    $sql = "
        SELECT
            p.id,
            p.dt_add,
            p.original_post_id,
            p.user_id,
            p.title,
            p.body,
            p.author_name,
            p.views_count,
            (SELECT COUNT(`id`) FROM comments c WHERE c.post_id = p.id) as comments_count,
            (SELECT COUNT(`id`) FROM likes l WHERE l.post_id = p.id) as likes_count,
            c1.classname AS type
        FROM
            posts AS p
            LEFT JOIN content_types AS c1 ON p.content_type_id = c1.id
            LEFT JOIN comments AS c2 ON p.id = c2.post_id
            LEFT JOIN likes AS l ON p.id = l.post_id
        $subquery
        GROUP BY p.id
        $sorting_rule;

    ";

    $stmt = db_get_prepare_stmt($con, $sql, isset($search) ? [$search] : []);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $posts;
    }

    return null;
}

function get_posts_by_id(mysqli $con, ?int $id): ?array
{
    $subquery = is_null($id) ? "p.content_type_id = c.id" : "p.id = $id";

    $sql = "
        SELECT
            c.classname,
            p.id,
            p.title,
            p.body,
            p.author_name,
            u.login AS username,
            u.avatar_path AS userpic
        FROM
            posts AS p
            INNER JOIN users AS u ON p.user_id = u.id
            INNER JOIN content_types AS c ON c.id = p.content_type_id
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

function add_post(mysqli $con, array $form_data): bool
{
    $post_sql = '
        INSERT INTO
            posts (
                dt_add,
                user_id,
                views_count,
                title,
                body,
                content_type_id,
                author_name
            )
        VALUES
            (NOW(), 1, 0, ?, ?, ?, ?)
    ';

    $stmt = db_get_prepare_stmt(
        $con,
        $post_sql,
        [$form_data['title'], $form_data['body'], $form_data['content_type_id'], $form_data['author_name'] ?? null]
    );

    $res = mysqli_stmt_execute($stmt);

    return $res;
}
