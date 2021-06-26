<?php
require_once('./helpers.php');
require_once('./config/init.php');
require_once('./const.php');
require_once('./services/user.php');

$head = include_template('partials/head.php', ['title' => 'readme: блог, каким он должен быть']);
$symbols = include_template('partials/symbols.php');
$page_header = include_template('partials/authorization/header.php');
$page_footer = include_template('partials/footer.php', ['modifier' => 'main']);

$form_errors = [];
$user_credentials_errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input_data = remove_empty_form_values($_POST, $_FILES);

    [, $errors] = validate_user(
        $input_data,
        $FORM_FIELDS_VALIDATORS,
        $FORM_FIELDS_LABELS
    );

    $form_errors = $errors;

    if (!count($form_errors)) {
        [$user, $user_credentials_errors] = validate_user_credentials($con, $input_data);

        if (!count($user_credentials_errors)) {
            $_SESSION['user'] = $user;
            header("Location: /feed.php");
            die();
        }
    }
}

if (isset($_SESSION['user'])) {
    header("Location: /feed.php");
    die();
}

$page_content = include_template('partials/authorization/main.php', ['errors' => array_merge($form_errors, $user_credentials_errors)]);
$layout_content = include_template('partials/authorization/layout.php', [
    'head' => $head,
    'symbols' => $symbols,
    'page_header' => $page_header,
    'page_content' => $page_content,
    'page_footer' => $page_footer,
]);

print($layout_content);
