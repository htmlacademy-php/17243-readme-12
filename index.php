<?php
require_once('./helpers.php');
require_once('./config/init.php');
require_once('./const.php');
require_once('./services/user.php');
require_once('./models/users.php');

$head = include_template('partials/head.php', ['title' => 'readme: блог, каким он должен быть']);
$symbols = include_template('partials/symbols.php');
$page_header = include_template('partials/authorization/header.php');
$page_footer = include_template('partials/footer.php', ['modifier' => 'main']);

$form_errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input_data = remove_empty_form_values($_POST, $_FILES);

    [, $errors] = validate_user(
        $input_data,
        $FORM_FIELDS_VALIDATORS,
        $FORM_FIELDS_LABELS
    );

    $form_errors = $errors;

    if (!count($form_errors)) {
        $user = !count($form_errors) ? get_user($con, 'login', $input_data['login']) : null;

        if ($user) {
            if (password_verify($input_data['password'], $user['password'])) {
                $_SESSION['user'] = $user;
                header("Location: /feed.php");
                die();
            } else {
                $form_errors['password'] = 'Пароли не совпадают';
            }
        } else {
            $form_errors['login'] = 'Неверный логин';
        }
    }
}

if (isset($_SESSION['user'])) {
    header("Location: /feed.php");
    die();
}

$page_content = include_template('partials/authorization/main.php', ['errors' => $form_errors]);
$layout_content = include_template('partials/authorization/layout.php', [
    'head' => $head,
    'symbols' => $symbols,
    'page_header' => $page_header,
    'page_content' => $page_content,
    'page_footer' => $page_footer,
]);

print($layout_content);
