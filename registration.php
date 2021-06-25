<?php
require_once('./helpers.php');
require_once('./config/init.php');
require_once('./const.php');
require_once('./services/user.php');
require_once('./models/users.php');

$head = include_template('partials/head.php', ['title' => 'readme: регистрация']);
$symbols = include_template('partials/symbols.php');
$page_header = include_template('partials/header.php', [
    'username' => 'Вася Попкин',
    'is_being_registered' => true,
]);
$page_footer = include_template('partials/footer.php');

$form_errors = [];
$res = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input_data = remove_empty_form_values($_POST, $_FILES);

    [$validated_data, $errors] = validate_user(
        $input_data,
        $FORM_FIELDS_VALIDATORS,
        $FORM_FIELDS_LABELS
    );
    $form_errors = $errors;
    if (empty($form_errors)) {
        if (get_user($con, 'email', $input_data['email'])) {
            $form_errors['email'] = 'Пользователь с этим email уже зарегистрирован';
        } else {
            $res = create_user($con, array_merge(
                isset($validated_data['userpic-file']) ? ['userpic-file' => $validated_data['userpic-file']] : [],
                [
                    'email' => $validated_data['email'],
                    'login' => $validated_data['login'],
                    'password' => $validated_data['password']
                ]
            ));
        }

        if ($res && empty($form_errors)) {
            header("Location: /index.php");
            die();
        }
    }
}

$page_content = include_template('partials/registration/main.php', ['errors' => $form_errors]);
$layout_content = include_template('partials/registration/layout.php', [
    'head' => $head,
    'symbols' => $symbols,
    'page_header' => $page_header,
    'page_content' => $page_content,
    'page_footer' => $page_footer
]);

print($layout_content);
