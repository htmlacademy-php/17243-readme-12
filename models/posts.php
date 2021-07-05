<?php
function get_posts(
    mysqli $con,
    array $search_types,
    string $search_type,
    ...$params
): ?array {
    $subquery = $search_types[$search_type]($params);

    $sorting_rule = $search_type !== 'random_words' ? 'ORDER BY p.dt_add DESC' : '';

    $sql = "
        SELECT
            p.id,
            p.dt_add,
            p.user_id,
            p.title,
            p.body,
            p.author_name,
            p.url_desc,
            p.views_count,
            (
                SELECT
                    COUNT(`id`)
                FROM
                    comments c
                WHERE
                    c.post_id = p.id
            ) as comments_count,
            (
                SELECT
                    COUNT(`id`)
                FROM
                    likes l
                WHERE
                    l.post_id = p.id
            ) as likes_count,
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

    $stmt = db_get_prepare_stmt($con, $sql, !empty($params) ? array_filter($params) : []);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $posts;
    }

    return null;
}

function get_posts_by_content_type_id(mysqli $con, ?int $id): ?array
{
    $subquery = is_null($id) ? "p.content_type_id = c.id" : "p.content_type_id = $id";

    $sql = "
        SELECT
            c.classname,
            p.id,
            p.dt_add,
            p.title,
            p.body,
            p.author_name,
            p.url_desc,
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
    $sql = '
        INSERT INTO
            posts (
                dt_add,
                views_count,
                user_id,
                title,
                body,
                content_type_id,
                author_name,
                p.url_desc
            )
        VALUES
            (NOW(), 0, ?, ?, ?, ?, ?, ?)
    ';

    $stmt = db_get_prepare_stmt(
        $con,
        $sql,
        [
            $_SESSION['user']['id'],
            $form_data['title'],
            $form_data['body'],
            $form_data['content_type_id'],
            $form_data['author_name'] ?? null
        ]
    );

    $res = mysqli_stmt_execute($stmt);

    return $res;
}

function get_reposts_count(mysqli $con, ?int $id): int
{
    $count = 0;

    if (is_null($id)) {
        return $count;
    }

    $sql = '
        SELECT
            COUNT(p.original_post_id) AS reposts_count
        FROM
            posts AS p
        WHERE
            p.original_post_id = ?
        GROUP BY
            p.original_post_id
    ';

    $stmt = db_get_prepare_stmt($con, $sql, [$id]);
    mysqli_stmt_execute($stmt);
    $query_result = mysqli_stmt_get_result($stmt);

    if ($query_result) {
        $data = mysqli_fetch_array($query_result, MYSQLI_ASSOC);

        if (empty($data)) {
            return $count;
        } else {
            ['reposts_count' => $count] = $data;

            return $count;
        }
    }

    return $count;
}

function make_repost(mysqli $con, ?int $post_id, ?int $user_id): void
{
    $sql = '
        SELECT
        *
        FROM
            posts AS p
        WHERE
            p.id = ?
            AND p.id NOT IN (
                SELECT
                    p.original_post_id
                FROM
                    posts p
                WHERE
                    p.user_id = ?
                    AND p.original_post_id IS NOT NULL
            )
    ';

    $stmt = db_get_prepare_stmt($con, $sql, [$post_id, $user_id]);
    mysqli_stmt_execute($stmt);
    $query_result = mysqli_stmt_get_result($stmt);

    if ($query_result) {
        $post = mysqli_fetch_array($query_result, MYSQLI_ASSOC);

        if (!empty($post)) {
            $sql = '
                INSERT INTO
                    posts (
                        dt_add,
                        original_post_id,
                        title,
                        body,
                        views_count,
                        user_id,
                        content_type_id,
                        author_name,
                        url_desc
                    )
                VALUES
                    (NOW(), ?, ?, ?, ?, ?, ?, ?, ?)
    ';

            $stmt = db_get_prepare_stmt(
                $con,
                $sql,
                [
                    $post_id,
                    $post['title'],
                    $post['body'],
                    $post['views_count'],
                    $_SESSION['user']['id'],
                    $post['content_type_id'],
                    $post['author_name'] ?? null,
                    $post['url_desc'] ?? null
                ]
            );

            $query_result = mysqli_stmt_execute($stmt);

            if ($query_result) {
                header("Location: /profile.php");
            }
        }
    }
}
