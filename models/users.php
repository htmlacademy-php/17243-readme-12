<?php
require_once('./helpers.php');

function get_user_details_by_id($con, $id): ?array
{
    $sql = "
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
                    s.user_id = u.id
            ) AS subscribers_count,
            (
                SELECT
                    COUNT(`id`)
                FROM
                    posts p
                WHERE
                    p.user_id = u.id
            ) AS posts_count
        FROM
            users AS u
            INNER JOIN posts AS p ON p.user_id = u.id
            AND p.id = ?
";

    $stmt = db_get_prepare_stmt($con, $sql, [$id]);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $user_details = mysqli_fetch_assoc($result);

        return $user_details;
    }

    return null;
}
