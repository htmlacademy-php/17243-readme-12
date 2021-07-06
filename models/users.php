<?php
require_once('./helpers.php');

function get_user_details_by_post_id(mysqli $con, int $id): ?array
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

function get_user_by_id(mysqli $con, int $id): ?array
{
    if (is_null($id)) {
        return null;
    }

    $sql = "
        SELECT
            *
        FROM
            users
        WHERE
            id = ?
    ";

    $stmt = db_get_prepare_stmt($con, $sql, [$id]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $user = mysqli_fetch_array($result, MYSQLI_ASSOC);

        return $user;
    }

    return null;
}

function get_user_by_field(mysqli $con, string $field_name, string $value): ?array
{
    $esc_value = mysqli_real_escape_string($con, $value);

    $sql = "
        SELECT
            *
        FROM
            users
        WHERE
            $field_name = '$esc_value'
    ";

    $res = mysqli_query($con, $sql);

    return $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;
}

function create_user(mysqli $con, array $form_data): bool
{
    ['email' => $email, 'login' => $login, 'password' => $password] = array_filter($form_data);

    $is_userpic_exist = isset($form_data['userpic-file']) && $form_data['userpic-file']['name'];

    if ($is_userpic_exist) {
        move_uploaded_file($form_data['userpic-file']['tmp_name'], 'uploads/' . $form_data['userpic-file']['name']);
    }

    $password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "
        INSERT INTO
            users (dt_add, email, login, password, avatar_path)
        VALUES
            (NOW(), ?, ?, ?, ?)
    ";
    $stmt = db_get_prepare_stmt(
        $con,
        $sql,
        [
            $email,
            $login,
            $password,
            $is_userpic_exist ? $form_data['userpic-file']['name'] : null
        ]
    );
    $res = mysqli_stmt_execute($stmt);

    return $res;
}
