<?php
require_once('./helpers.php');

function link_tag_with_post(mysqli $con, array $data): bool
{
    $tag_post_sql = '
        INSERT INTO
            posts_has_hashtags (post_id, hashtag_id)
        VALUES
            (?, ?)
    ';

    $stmt = db_get_prepare_stmt(
        $con,
        $tag_post_sql,
        $data
    );

    $res = mysqli_stmt_execute($stmt);

    return $res;
}
