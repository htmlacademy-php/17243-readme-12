<?php
function get_content_types(mysqli $con): ?array
{
    $sql = 'SELECT * FROM content_types';

    $result = mysqli_query($con, $sql);

    if ($result) {
        $content_types = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $content_types;
    }
    return null;
}
