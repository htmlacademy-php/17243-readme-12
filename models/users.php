<?php
require_once('./helpers.php');

function get_user_details_by_id(object $con, int $id): ?array
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

function get_users_count(object $con, string $field_name)
{
    $email = mysqli_real_escape_string($con, $field_name);
    $sql = "
    SELECT
        id
    FROM
        users
    WHERE
        email = '$email'
    ";
    $res = mysqli_query($con, $sql);

    return mysqli_num_rows($res);
}

function create_user(object $con, $form_data)
{
    ['email' => $email, 'login' => $login, 'password' => $password] = array_filter($form_data);

    if (isset($form_data['userpic-file'])) {
        move_uploaded_file($form_data['userpic-file']['tmp_name'], 'uploads/' . $form_data['userpic-file']['name']);
    }

    $password = password_hash($password, PASSWORD_DEFAULT);
    $sql = '
        INSERT INTO
            users (dt_add, email, login, password, avatar_path)
        VALUES
            (NOW(), ?, ?, ?, ?)
    ';
    $stmt = db_get_prepare_stmt($con, $sql, [
        $email,
        $login,
        $password,
        isset($form_data['userpic-file']) ? $form_data['userpic-file']['name'] : null,
    ]);
    $res = mysqli_stmt_execute($stmt);

    return $res;
}
