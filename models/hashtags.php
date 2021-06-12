<?php
require_once('./helpers.php');

function get_hashtags_by_id($con, $id): ?array
{
    $sql = "
        SELECT
            name
        FROM
            hashtags
            INNER JOIN posts_has_hashtags  ON hashtags.id = posts_has_hashtags.hashtag_id
        WHERE
            posts_has_hashtags.post_id = ?;
";

    $stmt = db_get_prepare_stmt($con, $sql, [$id]);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $hashtags = mysqli_fetch_assoc($result);

        return $hashtags;
    }

    return null;
}
