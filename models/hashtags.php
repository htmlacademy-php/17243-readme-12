<?php
function get_hashtags_by_id($con, $id): ?array
{
    $sql = "
        SELECT
            name
        FROM
            hashtags
            INNER JOIN posts_has_hashtags  ON hashtags.id = posts_has_hashtags.hashtags_id
        WHERE
            posts_has_hashtags.posts_id = ?;
";

    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $hashtags = mysqli_fetch_assoc($result);

        return $hashtags;
    }

    return null;
}
