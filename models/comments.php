<?php
function get_post_comments_by_id($con, int $id): ?array
{
    $sql = "
    SELECT
        c1.dt_add,
        c1.text,
        c2.login AS username,
        c2.avatar_path AS userpic
    FROM
        comments AS c1
        INNER JOIN posts AS p ON p.id = c1.posts_id
        AND p.id = ?
        INNER JOIN users AS c2 ON c2.id = c1.users_id;
";

    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $comments = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $comments;
    }

    return null;
}
