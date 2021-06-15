<?php
require_once('./helpers.php');

function get_post_comments_by_id($con, $id)
{
    $sql = "
    SELECT
        c1.dt_add,
        c1.text,
        c2.login AS username,
        c2.avatar_path AS userpic
    FROM
        comments AS c1
        INNER JOIN posts AS p ON p.id = c1.post_id
        AND p.id = ?
        INNER JOIN users AS c2 ON c2.id = c1.user_id;
";

    $stmt = db_get_prepare_stmt($con, $sql, [$id]);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $comments = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $comments;
    }

    return null;
}
